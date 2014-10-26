<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\AndroidPass;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;

class AndroidPassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new AndroidPass();
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

    public function testDoesNotSetAndroidURI()
    {
        $event = new ResourceEvent(new HtmlPage, new Response(200), new Crawler);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->hasAndroidURI(), false);
    }

    public function testSetAndroidURI()
    {
        $dom = new Crawler;
        $dom->addContent('<html><head><link rel="alternate" href="android-app://com.google.foo"></head></html>');
        $event = new ResourceEvent(new HtmlPage, new Response(200), $dom);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->hasAndroidURI(), true);
        $this->assertEquals($event->getResource()->getAndroidURI(), 'android-app://com.google.foo');
    }
}
