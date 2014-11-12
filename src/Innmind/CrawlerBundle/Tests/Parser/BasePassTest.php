<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\BasePass;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;

class BasePassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new BasePass;
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

    public function testSetBase()
    {
        $dom = new Crawler;
        $dom->addContent('<!DOCTYPE html>
            <html>
                <head>
                    <base href="http://innmind.io/">
                </head>
            </html>');
        $event = new ResourceEvent(new HtmlPage, new Response(200), $dom);

        $this->pass->handle($event);

        $this->assertTrue($event->getResource()->hasBase());
        $this->assertEquals('http://innmind.io/', $event->getResource()->getBase());
    }
}
