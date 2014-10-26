<?php

namespace Innmind\CrawlerBundle\Tests;

use Innmind\CrawlerBundle\UriResolver;
use Innmind\CrawlerBundle\Entity\Resource;
use SYmfony\Component\Validator\Validation;

class UriResolverTest extends \PHPUnit_Framework_TestCase
{
    protected $resolver;
    protected $resource;

    public function setUp()
    {
        $this->resolver = new UriResolver;
        $this->resolver->setValidator(Validation::createValidator());
        $this->resource = new Resource;
        $this->resource
            ->setScheme('http')
            ->setHost('innmind.io')
            ->setPort(8080)
            ->setPath('/home')
            ->setQuery('foo=bar')
            ->setFragment('foobar');
    }

    public function testGetFromFullUri()
    {
        $this->assertEquals(
            'http://innmind.fr/foo',
            $this->resolver->resolve(
                'http://innmind.fr/foo',
                $this->resource
            )
        );
    }

    public function testGetFromAbsolutePath()
    {
        $this->assertEquals(
            'http://innmind.io:8080/foo/bar?baz#baz',
            $this->resolver->resolve(
                '/foo/bar?baz#baz',
                $this->resource
            )
        );
    }

    public function testGetFromQuery()
    {
        $this->assertEquals(
            'http://innmind.io:8080/home?baz#baz',
            $this->resolver->resolve(
                '?baz#baz',
                $this->resource
            )
        );
    }

    public function testGetFromFragment()
    {
        $this->assertEquals(
            'http://innmind.io:8080/home?foo=bar#baz',
            $this->resolver->resolve(
                '#baz',
                $this->resource
            )
        );
    }

    public function testGetFromRelativePath()
    {
        $this->assertEquals(
            'http://innmind.io:8080/foo',
            $this->resolver->resolve(
                'foo',
                $this->resource
            )
        );

        $this->resource->setPath('/home/');

        $this->assertEquals(
            'http://innmind.io:8080/home/foo',
            $this->resolver->resolve(
                'foo',
                $this->resource
            )
        );
    }
}
