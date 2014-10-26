<?php

namespace Innmind\CrawlerBundle\Tests\Normalization;

use Innmind\CrawlerBundle\Normalization\DataSet;

class DataSetTest extends \PHPUnit_Framework_TestCase
{
    public function testSetData()
    {
        $data = new DataSet;

        $this->assertEquals($data, $data->set('foo', 'bar'));
        $this->assertEquals(['foo' => 'bar'], $data->getArray());
    }
}
