<?php

namespace Innmind\CrawlerBundle\Tests\Entity;

use Innmind\CrawlerBundle\Entity\Resource;
use Doctrine\Common\Collections\ArrayCollection;

class ResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testSetURI()
    {
        $r = new Resource();

        $this->assertEquals($r->getUri(), null);
        $this->assertEquals($r->setUri('foo'), $r);
        $this->assertEquals($r->getUri(), 'foo');
    }

    public function testSetScheme()
    {
        $r = new Resource();

        $this->assertEquals($r->getScheme(), null);
        $this->assertEquals($r->setScheme('http'), $r);
        $this->assertEquals($r->getScheme(), 'http');
    }

    public function testSetHost()
    {
        $r = new Resource();

        $this->assertEquals($r->getHost(), null);
        $this->assertEquals($r->setHost('innmind.io'), $r);
        $this->assertEquals($r->getHost(), 'innmind.io');
    }

    public function testSetDomain()
    {
        $r = new Resource();

        $this->assertEquals($r->getDomain(), null);
        $this->assertEquals($r->setDomain('innmind'), $r);
        $this->assertEquals($r->getDomain(), 'innmind');
    }

    public function testSetTopLevelDomain()
    {
        $r = new Resource();

        $this->assertEquals($r->getTopLevelDomain(), null);
        $this->assertEquals($r->setTopLevelDomain('io'), $r);
        $this->assertEquals($r->getTopLevelDomain(), 'io');
    }

    public function testSetPort()
    {
        $r = new Resource();

        $this->assertEquals($r->hasPort(), false);
        $this->assertEquals($r->setPort('80'), $r);
        $this->assertEquals($r->hasPort(), true);
        $this->assertEquals($r->getPort(), 80);
    }

    public function testSetURL()
    {
        $r = new Resource();

        $this->assertEquals($r->getURL(), '/');
        $this->assertEquals($r->setURL('/foo/bar'), $r);
        $this->assertEquals($r->getURL(), '/foo/bar');
    }

    public function testSetFragment()
    {
        $r = new Resource();

        $this->assertEquals($r->hasFragment(), false);
        $this->assertEquals($r->setFragment('foo'), $r);
        $this->assertEquals($r->hasFragment(), true);
        $this->assertEquals($r->getFragment(), 'foo');
    }

    public function testSetHeader()
    {
        $r = new Resource();

        $this->assertEquals($r->hasHeader('Content-Type'), false);
        $this->assertEquals($r->addHeader('Content-Type', 'text/plain'), $r);
        $this->assertEquals($r->hasHeader('Content-Type'), true);
        $this->assertEquals($r->getHeader('Content-Type'), 'text/plain');
        $this->assertEquals($r->getHeaders() instanceof ArrayCollection, true);
    }
}
