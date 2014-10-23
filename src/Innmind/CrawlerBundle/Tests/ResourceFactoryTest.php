<?php

namespace Innmind\CrawlerBundle\Tests;

use Innmind\CrawlerBundle\ResourceFactory;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;

class ResourceFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $factory;

    public function setUp()
    {
        $this->factory = new ResourceFactory();
    }

    public function testReturnResourceByDefault()
    {
        $this->assertTrue($this->factory->make('') instanceof Resource);
    }

    public function testReturnHtmlPage()
    {
        $this->assertTrue($this->factory->make('text/html') instanceof HtmlPage);
        $this->assertTrue($this->factory->make('application/xhtml+xml') instanceof HtmlPage);
    }
}
