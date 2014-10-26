<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\UriResolver;

/**
 * Retrieve canonical url (if any)
 */
class CanonicalPass
{
    protected $resolver;

    /**
     * Set uri resolver
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

        $dom = $event->getDOM();
        $canonical = $dom->filter('link[rel="canonical"][href]');

        if ($canonical->count() === 1) {
            $canonical = $canonical->attr('href');

            $resource->setCanonical(
                $this->resolver->resolve(
                    $canonical,
                    $resource
                )
            );
        }
    }
}
