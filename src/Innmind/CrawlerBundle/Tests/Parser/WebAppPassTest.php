<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\WebAppPass;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;

class WebAppPassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new WebAppPass();
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

    public function testDoesNotSetWebApp()
    {
        $event = new ResourceEvent(new HtmlPage, new Response(200), new Crawler);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->hasWebApp(), false);
    }

    public function testSetWebApp()
    {
        $dom = new Crawler;
        $dom->addContent('<html><head><link rel="manifest" href="manifest.json"></head></html>');
        $event = new ResourceEvent(new HtmlPage, new Response(200), $dom);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->hasWebApp(), true);
    }
}
