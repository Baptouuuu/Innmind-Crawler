<?php

namespace Innmind\CrawlerBundle\Tests\Normalization\Pass;

use Innmind\CrawlerBundle\Normalization\Pass\ImagePass;
use Innmind\CrawlerBundle\Entity\Image;
use Innmind\CrawlerBundle\Normalization\DataSet;

class ImagePassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new ImagePass;
    }

    public function testSetBasicValues()
    {
        $image = new Image;
        $data = new DataSet;

        $image
            ->setHeight(42)
            ->setWidth(42)
            ->setMime('image/png')
            ->setExtension('.png');

        $this->pass->normalize($image, $data);

        $this->assertEquals(
            [
                'height' => 42,
                'width' => '42',
                'mime' => 'image/png',
                'extension' => '.png',
            ],
            $data->getArray()
        );
    }

    public function testSetWeight()
    {
        $image = new Image;
        $data = new DataSet;

        $image->setWeight(75000);

        $this->pass->normalize($image, $data);

        $this->assertTrue(isset($data->getArray()['weight']));
        $this->assertTrue(isset($data->getArray()['readable-weight']));
        $this->assertEquals(
            75000,
            $data->getArray()['weight']
        );
        $this->assertEquals(
            '73 Ko',
            $data->getArray()['readable-weight']
        );
    }

    public function testSetExif()
    {
        $image = new Image;
        $data = new DataSet;

        $image->addExif('foo', 'bar');

        $this->pass->normalize($image, $data);

        $this->assertTrue(isset($data->getArray()['exif']));
        $this->assertEquals(
            ['foo' => 'bar'],
            $data->getArray()['exif']
        );
    }
}
