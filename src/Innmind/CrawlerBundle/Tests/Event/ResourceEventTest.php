<?php

namespace Innmind\CrawlerBundle\Tests\Event;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\ResourceRequest;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;

class ResourceEventTest extends \PHPUnit_Framework_TestCase
{
    public function testThrowIfInvalidResource()
    {
        try {
            new ResourceEvent();

            $this->assertEquals(false, true);
        } catch (\Exception $e) {
            $this->assertEquals(true, true);
        }
    }

    public function testThrowIfInvalidResponse()
    {
        try {
            new ResourceEvent(new Resource);

            $this->assertEquals(false, true);
        } catch (\Exception $e) {
            $this->assertEquals(true, true);
        }
    }

    public function testThrowIfInvalidDomCrawler()
    {
        try {
            new ResourceEvent(new Resource, new Response(200));

            $this->assertEquals(false, true);
        } catch (\Exception $e) {
            $this->assertEquals(true, true);
        }
    }

    public function testGetResource()
    {
        $r = new Resource;
        $e = new ResourceEvent($r, new Response(200), new Crawler);

        $this->assertEquals($e->getResource(), $r);
    }

    public function testGetResponse()
    {
        $r = new Response(200);
        $e = new ResourceEvent(new Resource, $r, new Crawler);

        $this->assertEquals($e->getResponse(), $r);
    }

    public function testGetDOM()
    {
        $c = new Crawler;
        $e = new ResourceEvent(new Resource, new Response(200), $c);

        $this->assertEquals($e->getDOM(), $c);
    }

    public function testGetResourceRequest()
    {
        $req = new ResourceRequest;
        $e = new ResourceEvent(new Resource, new Response(200), new Crawler);
        $e->setResourceRequest($req);

        $this->assertEquals($req, $e->getResourceRequest());
    }
}
