<?php

namespace Innmind\CrawlerBundle\Tests\Normalization;

use Innmind\CrawlerBundle\Normalization\Normalizer;
use Innmind\CrawlerBundle\Normalization\Pass\HtmlPass;
use Innmind\CrawlerBundle\Entity\Resource;

class NormalizerTest extends \PHPUnit_Framework_TestCase
{
    public function testSetNormalizationPass()
    {
        $normalizer = new Normalizer;

        $this->assertEquals(
            null,
            $normalizer->addNormalizationPass(new HtmlPass)
        );
    }

    public function testNormalize()
    {
        $normalizer = new Normalizer;

        $this->assertEquals(
            [],
            $normalizer->normalize(new Resource)
        );
    }
}
