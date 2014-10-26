<?php

namespace Innmind\CrawlerBundle\Normalization;

use Innmind\CrawlerBundle\Entity\Resource;

interface NormalizationPassInterface
{
    /**
     * Extract data off the resource and set corresponding
     * raw data into the dataset
     *
     * @param Resource $resource
     * @param DataSet $dataset
     */

    public function normalize(Resource $resource, DataSet $dataset);
}
