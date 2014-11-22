<?php

namespace Innmind\CrawlerBundle\Normalization\Pass;

use Innmind\CrawlerBundle\Normalization\NormalizationPassInterface;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\Image;
use Innmind\CrawlerBundle\Normalization\DataSet;

class ImagePass implements NormalizationPassInterface
{
    /**
     * {@inheritdoc}
     */

    public function normalize(Resource $resource, DataSet $dataset)
    {
        if (!($resource instanceof Image)) {
            return;
        }

        $dataset
            ->set('height', $resource->getHeight())
            ->set('width', $resource->getWidth())
            ->set('mime', $resource->getMime())
            ->set('extension', $resource->getExtension());

        if ($resource->getWeight() !== null) {
            $weight = $resource->getWeight();
            $dataset->set('weight', $weight);

            if (($weight / 1024) < 1024) {
                $humanWeight = (string) round($weight / 1024);
                $humanWeight .= ' Ko';
            } else {
                $humanWeight = (string) round($weight / (1024 * 1024));
                $humanWeight .= ' Mo';
            }

            $dataset->set('readable-weight', $humanWeight);
        }

        if ($resource->hasExif()) {
            $dataset->set('exif', $resource->getExif()->toArray());
        }
    }
}
