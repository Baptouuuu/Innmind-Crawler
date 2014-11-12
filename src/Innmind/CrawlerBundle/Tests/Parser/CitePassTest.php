<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\CitePass;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;

class CitePassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new CitePass;
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

    public function testSetCite()
    {
        $dom = new Crawler;
        $dom->addContent('<!DOCTYPE html>
            <html>
                <body>
                    <cite>The Scream</cite>
                </body>
            </html>');
        $event = new ResourceEvent(new HtmlPage, new Response(200), $dom);

        $this->pass->handle($event);

        $this->assertEquals(1, $event->getResource()->getCitations()->count());
        $this->assertTrue($event->getResource()->getCitations()->contains('The Scream'));
    }
}
