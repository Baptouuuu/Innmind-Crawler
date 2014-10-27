<?php

namespace Innmind\CrawlerBundle\Normalization;

use Innmind\CrawlerBundle\Entity\Resource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Pass a resource to a set of normalization passes in order
 * to extract raw data off of the given resource
 */

class Normalizer
{
    /**
     * Set of normalization passes
     * @var ArrayCollection
     */

    protected $passes;

    public function __construct()
    {
        $this->passes = new ArrayCollection;
    }

    /**
     * Add a new normalization pass
     *
     * @param NormalizationPassInterface $pass
     */

    public function addNormalizationPass(NormalizationPassInterface $pass)
    {
        $this->passes->add($pass);
    }

    /**
     * Normalize the give resource
     *
     * @param Resource $resource
     *
     * @return array
     */

    public function normalize(Resource $resource)
    {
        $data = new DataSet;

        $this->passes->forAll(function ($idx, NormalizationPassInterface $pass) use ($data, $resource) {
            $pass->normalize($resource, $data);
            return true;
        });

        return $data->getArray();
    }
}
