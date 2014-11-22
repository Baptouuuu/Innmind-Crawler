<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\ImagePass;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\Image;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\ResourceRequest;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;

class ImagePassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new ImagePass;
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

    public function testSetImageData()
    {
        $event = new ResourceEvent(new Image, new Response(200, ['Content-Length' => 7000]), new Crawler);
        $request = new ResourceRequest;
        $request->setURI(getcwd().'/web/favicon.ico');
        $event->setResourceRequest($request);

        $this->pass->handle($event);

        $this->assertEquals(32, $event->getResource()->getHeight());
        $this->assertEquals(32, $event->getResource()->getWidth());
        $this->assertEquals(7000, $event->getResource()->getWeight());
        $this->assertEquals('image/vnd.microsoft.icon', $event->getResource()->getMime());
        $this->assertEquals('.ico', $event->getResource()->getExtension());
        $this->assertFalse($event->getResource()->hasExif());
    }
}
