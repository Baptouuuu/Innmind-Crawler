<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\TitlePass;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;

class TitlePassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new TitlePass();
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

    public function testSetTitleViaH1()
    {
        $dom = new Crawler();
        $dom->addContent('<html><body><h1>Innmind</h1></body></html>');
        $event = new ResourceEvent(new HtmlPage, new Response(200), $dom);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getTitle(), 'Innmind');
    }

    public function testSetTitleViaHead()
    {
        $dom = new Crawler();
        $dom->addContent('<html><head><title>Innmind</title></head><body><h2>foo</h2></body></html>');
        $event = new ResourceEvent(new HtmlPage, new Response(200), $dom);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getTitle(), 'Innmind');
    }
}
