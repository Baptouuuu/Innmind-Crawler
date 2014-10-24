<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\RssPass;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Validator\Validation;

class RssPassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new RssPass();
        $this->pass->setValidator(Validation::createValidator());
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

    public function testSetRSS()
    {
        $dom = new Crawler;
        $dom->addContent('<!DOCTYPE html>
            <html>
                <head>
                    <link rel="alternate" type="application/rss+xml" href="/some-feed.rss">
                </head>
            </html>');
        $resource = new HtmlPage;
        $resource
            ->setScheme('http')
            ->setHost('innmind.io')
            ->setPort(80);
        $event = new ResourceEvent($resource, new Response(200), $dom);

        $this->assertFalse($event->getResource()->hasRSS());

        $this->pass->handle($event);

        $this->assertTrue($event->getResource()->hasRSS());
        $this->assertEquals($event->getResource()->getRSS(), 'http://innmind.io/some-feed.rss');

        $resource->setPort(8080);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getRSS(), 'http://innmind.io:8080/some-feed.rss');

        $dom->clear();
        $dom->addContent('<!DOCTYPE html>
            <html>
                <head>
                    <link rel="alternate" type="application/rss+xml" href="http://innmind.io:443/some-feed.rss">
                </head>
            </html>');

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getRSS(), 'http://innmind.io:443/some-feed.rss');
    }
}
