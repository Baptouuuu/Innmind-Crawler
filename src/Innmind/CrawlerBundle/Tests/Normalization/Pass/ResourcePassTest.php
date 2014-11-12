<?php

namespace Innmind\CrawlerBundle\Tests\Normalization\Pass;

use Innmind\CrawlerBundle\Normalization\Pass\ResourcePass;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Normalization\DataSet;

class ResourcePassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new ResourcePass;
    }

    public function testSetBasicValues()
    {
        $resource = new Resource;
        $data = new DataSet;

        $resource
            ->setURI('http://innmind.io/')
            ->setScheme('http')
            ->setHost('innmind.io')
            ->setDomain('innmind')
            ->setTopLevelDomain('io')
            ->setPort(80)
            ->setPath('/');

        $this->pass->normalize($resource, $data);

        $this->assertEquals(
            [
                'uri' => 'http://innmind.io/',
                'scheme' => 'http',
                'host' => 'innmind.io',
                'domain' => 'innmind',
                'tld' => 'io',
                'port' => 80,
                'path' => '/'
            ],
            $data->getArray()
        );
    }

    public function testSetQuery()
    {
        $resource = new Resource;
        $data = new DataSet;

        $resource->setQuery('foo=bar');

        $this->pass->normalize($resource, $data);

        $this->assertTrue(isset($data->getArray()['query']));
        $this->assertEquals(
            'foo=bar',
            $data->getArray()['query']
        );
    }

    public function testSetFragment()
    {
        $resource = new Resource;
        $data = new DataSet;

        $resource->setFragment('/foo/bar');

        $this->pass->normalize($resource, $data);

        $this->assertTrue(isset($data->getArray()['fragment']));
        $this->assertEquals(
            '/foo/bar',
            $data->getArray()['fragment']
        );
    }

    public function testSetContentType()
    {
        $resource = new Resource;
        $data = new DataSet;

        $resource->addHeader('Content-Type', 'text/plain');

        $this->pass->normalize($resource, $data);

        $this->assertTrue(isset($data->getArray()['content-type']));
        $this->assertEquals(
            'text/plain',
            $data->getArray()['content-type']
        );
    }
}
