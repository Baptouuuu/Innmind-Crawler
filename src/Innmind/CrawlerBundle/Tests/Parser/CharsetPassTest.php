<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\CharsetPass;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Validator\Validation;

class CharsetPassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new CharsetPass();
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

    public function testSetCharsetViaHeader()
    {
        $resource = new HtmlPage;
        $resource->addHeader('Content-Type', 'text/html; charset=utf-8');
        $event = new ResourceEvent($resource, new Response(200), new Crawler);

        $this->assertEquals($event->getResource()->getCharset(), null);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getCharset(), 'utf-8');
    }

    public function testSetCharsetViaMetaTag()
    {
        $dom = new Crawler;
        $dom->addContent('<!DOCTYPE html>
            <html>
                <head>
                    <meta charset="utf-8">
                </head>
            </html>');
        $event = new ResourceEvent(new HtmlPage, new Response(200), $dom);

        $this->assertEquals($event->getResource()->getCharset(), null);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getCharset(), 'utf-8');
    }
}
