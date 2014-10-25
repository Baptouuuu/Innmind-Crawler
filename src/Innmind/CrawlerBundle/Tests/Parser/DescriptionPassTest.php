<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\DescriptionPass;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;

class DescriptionPassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new DescriptionPass;
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

    public function testSetDescriptionViaMetaTag()
    {
        $dom = new Crawler;
        $dom->addContent('<!DOCTYPE html>
            <html>
                <head>
                    <meta name="description" content="Test">
                </head>
            </html>');
        $event = new ResourceEvent(new HtmlPage, new Response(200), $dom);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getDescription(), 'Test');
    }

    public function testSetDescriptionViaContent()
    {
        $dom = new Crawler;
        $dom->addContent('<!DOCTYPE html><html></html>');
        $resource = new HtmlPage;
        $resource->setContent('This project is an attempt to build a search engine that helps you discover new things.
    The prime goal is to offer results based on your search, but instead of redirecting you
    to the website, Innmind will also propose content related to the one you\'re about to reach.');
        $event = new ResourceEvent($resource, new Response(200), $dom);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getDescription(), 'This project is an attempt to build a search engine that helps you discover new things.    The prime goal is to offer results based on your search, b...');
    }
}
