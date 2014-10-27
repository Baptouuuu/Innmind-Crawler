<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\UriResolver;

/**
 * Retrieve links to external resources
 */
class LinksPass
{
    protected $resolver;

    /**
     * Set the uri resolver
     *
     * @param UriResolver $resolver
     */

    public function setUriResolver(UriResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Process the crawled resource
     *
     * @param ResourceEvent $event
     */

    public function handle(ResourceEvent $event)
    {
        $resource = $event->getResource();

        if (!($resource instanceof HtmlPage)) {
            return;
        }

        $event
            ->getDOM()
            ->filter('
                a[href],
                link[rel="first"][href],
                link[rel="next"][href],
                link[rel="previous"][href],
                link[rel="last"][href]
            ')
            ->each(function (Crawler $node) use ($resource) {
                $href = $node->attr('href');

                $resource->addLink(
                    $this->resolver->resolve(
                        $href,
                        $resource
                    )
                );
            });
    }
}
