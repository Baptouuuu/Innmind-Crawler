<?php

namespace Innmind\CrawlerBundle\Tests;

use Pdp\Parser;
use Innmind\CrawlerBundle\DomainParserFactory;

class DomainParserFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testMake()
    {
        $factory = new DomainParserFactory();

        $this->assertTrue($factory->make() instanceof Parser);
    }
}
