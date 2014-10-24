<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\LanguagePass;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;

class LanguagePassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new LanguagePass;
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

    public function testSetLanguageViaHeader()
    {
        $dom = new Crawler;
        $dom->addContent('<!DOCTYPE html><html lang="fr, en"></html>');
        $event = new ResourceEvent(
            new HtmlPage,
            new Response(200, ['Content-Language' => 'en, fr']),
            $dom
        );

        $this->assertEquals($event->getResource()->getLanguage(), null);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getLanguage(), 'en');
    }

    public function testSetLanguageViaLangAttribute()
    {
        $dom = new Crawler;
        $dom->addContent('<!DOCTYPE html>
            <html lang="fr, en">
                <head>
                    <meta http-equiv="Content-Language" content="en, fr"
                </head>
            </html>');
        $event = new ResourceEvent(
            new HtmlPage,
            new Response(200),
            $dom
        );

        $this->assertEquals($event->getResource()->getLanguage(), null);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getLanguage(), 'fr');
    }

    public function testSetLanguageViaMetaTag()
    {
        $dom = new Crawler;
        $dom->addContent('<!DOCTYPE html>
            <html>
                <head>
                    <meta http-equiv="Content-Language" content="en, fr"
                </head>
            </html>');
        $event = new ResourceEvent(
            new HtmlPage,
            new Response(200),
            $dom
        );

        $this->assertEquals($event->getResource()->getLanguage(), null);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getLanguage(), 'en');
    }
}
