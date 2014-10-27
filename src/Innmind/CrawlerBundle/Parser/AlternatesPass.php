<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\UriResolver;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Retrieve translations for current resource
 */
class AlternatesPass
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

        $dom = $event->getDOM();
        $alternates = $dom->filter('link[rel="alternate"][href][hreflang]');

        if ($alternates->count() > 0) {
            $alternates->each(function (Crawler $node) use ($resource) {
                $resource->addAlternate(
                    $node->attr('hreflang'),
                    $this->resolver->resolve(
                        $node->attr('href'),
                        $resource
                    )
                );
            });
        }
    }
}
