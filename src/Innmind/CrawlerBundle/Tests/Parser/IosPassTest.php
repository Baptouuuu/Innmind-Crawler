<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\IosPass;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;

class IosPassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new IosPass();
    }

    public function testDoesNotHandleIfNotHtmlPage()
    {
        $event = new ResourceEvent(new Resource, new Response(200), new Crawler);

        try {
            $this->pass->handle($event);
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->assertTrue(false, 'Should not throw if handling raw resource');
        }
    }

    public function testDoesNotSetIosURI()
    {
        $event = new ResourceEvent(new HtmlPage, new Response(200), new Crawler);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->hasIosURI(), false);
    }

    public function testSetIosURI()
    {
        $dom = new Crawler;
        $dom->addContent('<html>
            <head>
                <meta name="apple-itunes-app" content="app-id=42, affiliate-data=foo, app-argument=innmind://">
            </head>
        </html>');
        $event = new ResourceEvent(new HtmlPage, new Response(200), $dom);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->hasIosURI(), true);
        $this->assertEquals($event->getResource()->getIosURI(), 'innmind://');
    }
}
