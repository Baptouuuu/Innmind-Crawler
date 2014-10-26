<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\JournalPass;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;

class JournalPassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new JournalPass();
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

    public function testDoesNotSetAsJournal()
    {
        $dom = new Crawler;
        $dom->addContent('<html>
            <body>
                <article></article>
            <body>
        </html>');
        $event = new ResourceEvent(new HtmlPage, new Response(200), $dom);

        $this->pass->handle($event);

        $this->assertFalse($event->getResource()->isJournal());
    }

    public function testSetAsJournal()
    {
        $dom = new Crawler;
        $dom->addContent('<html>
            <body>
                <article></article>
                <article></article>
            <body>
        </html>');
        $event = new ResourceEvent(new HtmlPage, new Response(200), $dom);

        $this->pass->handle($event);

        $this->assertTrue($event->getResource()->isJournal());
    }
}
