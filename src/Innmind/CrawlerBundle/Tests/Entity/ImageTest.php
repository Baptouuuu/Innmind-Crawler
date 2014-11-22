<?php

namespace Innmind\CrawlerBundle\Tests\Entity;

use Innmind\CrawlerBundle\Entity\Image;
use Doctrine\Common\Collections\ArrayCollection;

class ImageTest extends \PHPUnit_Framework_TestCase
{
    public function testSetHeight()
    {
        $r = new Image();

        $this->assertEquals(null, $r->getHeight());
        $this->assertEquals($r, $r->setHeight('42.0'));
        $this->assertEquals(42, $r->getHeight());
    }

    public function testSetWidth()
    {
        $r = new Image();

        $this->assertEquals(null, $r->getWidth());
        $this->assertEquals($r, $r->setWidth('42.0'));
        $this->assertEquals(42, $r->getWidth());
    }

    public function testSetWeight()
    {
        $r = new Image();

        $this->assertEquals(null, $r->getWeight());
        $this->assertEquals($r, $r->setWeight('42.0'));
        $this->assertEquals(42, $r->getWeight());
    }

    public function testSetMime()
    {
        $r = new Image();

        $this->assertEquals(null, $r->getMime());
        $this->assertEquals($r, $r->setMime('image/png'));
        $this->assertEquals('image/png', $r->getMime());
    }

    public function testSetExtension()
    {
        $r = new Image();

        $this->assertEquals(null, $r->getExtension());
        $this->assertEquals($r, $r->setExtension('.png'));
        $this->assertEquals('.png', $r->getExtension());
    }

    public function testSetExif()
    {
        $r = new Image();

        $this->assertFalse($r->hasExif());
        $this->assertEquals($r, $r->addExif('key', 42));
        $this->assertTrue($r->hasExif());
        $this->assertEquals(
            ['key' => '42'],
            $r->getExif()->toArray()
        );
    }
}
