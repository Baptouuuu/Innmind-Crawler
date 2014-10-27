<?php

namespace Innmind\CrawlerBundle\Normalization\Pass;

use Innmind\CrawlerBundle\Normalization\NormalizationPassInterface;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Normalization\DataSet;

class ResourcePass implements NormalizationPassInterface
{
    /**
     * {@inheritdoc}
     */

    public function normalize(Resource $resource, DataSet $dataset)
    {
        $dataset
            ->set('uri', $resource->getURI())
            ->set('scheme', $resource->getScheme())
            ->set('host', $resource->getHost())
            ->set('domain', $resource->getDomain())
            ->set('tld', $resource->getTopLevelDomain())
            ->set('port', $resource->getPort())
            ->set('path', $resource->getPath());

        if ($resource->hasQuery()) {
            $dataset->set('query', $resource->getQuery());
        }

        if ($resource->hasFragment()) {
            $dataset->set('fragment', $resource->getFragment());
        }
    }
}