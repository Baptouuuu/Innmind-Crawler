<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\UriResolver;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Retrieve all the citations in the page
 */
class CitePass
{
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
        $citations = $dom->filter('cite');

        if ($citations->count() > 0) {
            $citations->each(function (Crawler $node) use ($resource) {
                $resource->addCite($node->text());
            });
        }
    }
}
