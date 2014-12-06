<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\ThemeColorPass;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;

class ThemeColorPassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new ThemeColorPass;
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

    public function testSetShortHex()
    {
        $dom = new Crawler;
        $dom->addContent('<!DOCTYPE html>
            <html>
                <head>
                    <meta name="theme-color" content="#39f" />
                </head>
            </html>');
        $event = new ResourceEvent(new HtmlPage, new Response(200), $dom);

        $this->pass->handle($event);

        $this->assertTrue($event->getResource()->hasThemeColor());
        $this->assertEquals(58, $event->getResource()->getThemeColorHue());
        $this->assertEquals(100, $event->getResource()->getThemeColorSaturation());
        $this->assertEquals(60, $event->getResource()->getThemeColorLightness());
    }

    public function testSetLongHex()
    {
        $dom = new Crawler;
        $dom->addContent('<!DOCTYPE html>
            <html>
                <head>
                    <meta name="theme-color" content="#3399ff" />
                </head>
            </html>');
        $event = new ResourceEvent(new HtmlPage, new Response(200), $dom);

        $this->pass->handle($event);

        $this->assertTrue($event->getResource()->hasThemeColor());
        $this->assertEquals(58, $event->getResource()->getThemeColorHue());
        $this->assertEquals(100, $event->getResource()->getThemeColorSaturation());
        $this->assertEquals(60, $event->getResource()->getThemeColorLightness());
    }

    public function testSetRgb()
    {
        $dom = new Crawler;
        $dom->addContent('<!DOCTYPE html>
            <html>
                <head>
                    <meta name="theme-color" content="rgb(51, 153, 255)" />
                </head>
            </html>');
        $event = new ResourceEvent(new HtmlPage, new Response(200), $dom);

        $this->pass->handle($event);

        $this->assertTrue($event->getResource()->hasThemeColor());
        $this->assertEquals(58, $event->getResource()->getThemeColorHue());
        $this->assertEquals(100, $event->getResource()->getThemeColorSaturation());
        $this->assertEquals(60, $event->getResource()->getThemeColorLightness());
    }

    public function testSetHsl()
    {
        $dom = new Crawler;
        $dom->addContent('<!DOCTYPE html>
            <html>
                <head>
                    <meta name="theme-color" content="hsl(58, 100%, 60%)" />
                </head>
            </html>');
        $event = new ResourceEvent(new HtmlPage, new Response(200), $dom);

        $this->pass->handle($event);

        $this->assertTrue($event->getResource()->hasThemeColor());
        $this->assertEquals(58, $event->getResource()->getThemeColorHue());
        $this->assertEquals(100, $event->getResource()->getThemeColorSaturation());
        $this->assertEquals(60, $event->getResource()->getThemeColorLightness());
    }
}
