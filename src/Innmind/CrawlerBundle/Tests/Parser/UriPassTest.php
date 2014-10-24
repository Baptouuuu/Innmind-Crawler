<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\UriPass;
use Innmind\CrawlerBundle\DomainParserFactory;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\Resource;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;

class UriPassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new UriPass();
        $factory = new DomainParserFactory();
        $this->pass->setDomainParser($factory->make());
    }

    public function testSetScheme()
    {
        $resource = new Resource();
        $resource->setURI('https://localhost/');
        $event = new ResourceEvent($resource, new Response(200), new Crawler);

        $this->pass->handle($event);

        $this->assertEquals($resource->getScheme(), 'https');
    }

    public function testSetHost()
    {
        $resource = new Resource();
        $resource->setURI('https://www.innmind.io/');
        $event = new ResourceEvent($resource, new Response(200), new Crawler);

        $this->pass->handle($event);

        $this->assertEquals($resource->getHost(), 'www.innmind.io');
    }

    public function testSetDomain()
    {
        $resource = new Resource();
        $resource->setURI('https://www.innmind.io/');
        $event = new ResourceEvent($resource, new Response(200), new Crawler);

        $this->pass->handle($event);

        $this->assertEquals($resource->getDomain(), 'innmind.io');
    }

    public function testSetTopLevelDomain()
    {
        $resource = new Resource();
        $resource->setURI('https://www.innmind.io/');
        $event = new ResourceEvent($resource, new Response(200), new Crawler);

        $this->pass->handle($event);

        $this->assertEquals($resource->getTopLevelDomain(), 'io');
    }

    public function testSetPort()
    {
        $resource = new Resource();
        $resource->setURI('https://www.innmind.io:8080/');
        $event = new ResourceEvent($resource, new Response(200), new Crawler);

        $this->pass->handle($event);

        $this->assertEquals($resource->getPort(), 8080);
    }

    public function testSetPath()
    {
        $resource = new Resource();
        $resource->setURI('https://www.innmind.io');
        $event = new ResourceEvent($resource, new Response(200), new Crawler);

        $this->pass->handle($event);

        $this->assertEquals($resource->getPath(), '/');

        $resource->setURI('https://www.innmind.io/foo/bar');

        $this->pass->handle($event);

        $this->assertEquals($resource->getPath(), '/foo/bar');
    }

    public function testSetQuery()
    {
        $resource = new Resource();
        $resource->setURI('https://www.innmind.io/foo?bar=baz&foobar=foobaz');
        $event = new ResourceEvent($resource, new Response(200), new Crawler);

        $this->pass->handle($event);

        $this->assertEquals($resource->getQuery(), 'bar=baz&foobar=foobaz');
    }

    public function testSetFragment()
    {
        $resource = new Resource();
        $resource->setURI('https://www.innmind.io/foo#/foo/bar');
        $event = new ResourceEvent($resource, new Response(200), new Crawler);

        $this->pass->handle($event);

        $this->assertEquals($resource->getFragment(), '/foo/bar');
    }

    public function testSetHeaders()
    {
        $resource = new Resource();
        $resource->setURI('https://www.innmind.io/foo#/foo/bar');
        $headers = ['Content-Type' => 'text/html', 'Age' => 'IE=Edge;chrome=1'];
        $event = new ResourceEvent($resource, new Response(200, $headers), new Crawler);

        $this->pass->handle($event);

        $this->assertEquals($resource->getHeaders()->toArray(), $headers);
    }
}
