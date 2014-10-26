<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\UriResolver;

/**
 * Check if the document as an associated RSS feed
 */
class RssPass
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

    public function handle(ResourceEvent $event)
    {
        $resource = $event->getResource();

        if (!($resource instanceof HtmlPage)) {
            return;
        }

        $rss = $event
            ->getDOM()
            ->filter('link[rel="alternate"][type="application/rss+xml"][href]');

        if ($rss->count() === 1) {
            $rss = $rss->attr('href');

            $resource->setRSS(
                $this->resolver->resolve(
                    $rss,
                    $resource
                )
            );
        }
    }
}
