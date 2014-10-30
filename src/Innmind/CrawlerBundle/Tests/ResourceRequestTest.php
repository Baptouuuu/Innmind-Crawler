<?php

namespace Innmind\CrawlerBundle\Tests;

use Innmind\CrawlerBundle\ResourceRequest;

class ResourceRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testSetURI()
    {
        $r = new ResourceRequest();

        $this->assertEquals($r->getURI(), null);
        $this->assertEquals($r->setURI('http://localhost'), $r);
        $this->assertEquals($r->getURI(), 'http://localhost');
    }

    public function testAddHeader()
    {
        $r = new ResourceRequest();

        $this->assertEquals($r->getHeaders()->count(), 0);
        $this->assertEquals($r->addHeader('Acept-Language', 'fr-FR'), $r);
        $this->assertEquals($r->getHeaders()->count(), 1);
    }

    public function testAddHeaders()
    {
        $r = new ResourceRequest();

        $this->assertEquals($r->getHeaders()->count(), 0);
        $this->assertEquals($r->addHeaders(['Acept-Language' => 'fr-FR']), $r);
        $this->assertEquals($r->getHeaders()->count(), 1);
    }

    public function testSetPublisherURI()
    {
        $r = new ResourceRequest();

        $this->assertEquals($r, $r->setPublisherURI('foo'));
        $this->assertEquals('foo', $r->getPublisherURI());
    }

    public function testSetToken()
    {
        $r = new ResourceRequest();

        $this->assertEquals($r, $r->setToken('foo'));
        $this->assertEquals('foo', $r->getToken());
    }

    public function testSetUUID()
    {
        $r = new ResourceRequest;

        $this->assertFalse($r->hasUUID());
        $this->assertEquals($r, $r->setUUID('uuid'));
        $this->assertTrue($r->hasUUID());
        $this->assertEquals('uuid', $r->getUUID());
    }
}
