<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\AuthorPass;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;

class AuthorPassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new AuthorPass;
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

    public function testSetAuthor()
    {
        $dom = new Crawler;
        $dom->addContent('<!DOCTYPE html>
            <html>
                <head>
                    <link rel="author" content="Me">
                    <meta name="author" content="Myself">
                </head>
            </html>');
        $event = new ResourceEvent(new HtmlPage, new Response(200), $dom);

        $this->assertEquals($event->getResource()->getAuthor(), null);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getAuthor(), 'Myself');

        $dom->clear();
        $dom->addContent('<!DOCTYPE html><html></html>');

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getAuthor(), '');

        $dom->clear();
        $dom->addContent('<!DOCTYPE html>
            <html>
                <head>
                    <meta name="Author" content="Myself2">
                </head>
            </html>');

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getAuthor(), 'Myself2');

        $dom->clear();
        $dom->addContent('<!DOCTYPE html>
            <html>
                <head>
                    <meta name="AUTHOR" content="Myself3">
                </head>
            </html>');

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getAuthor(), 'Myself3');
    }
}
