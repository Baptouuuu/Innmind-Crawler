<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\UriResolver;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Retrieve all abbreviations found in the current resource
 */
class AbbreviationsPass
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
        $abbrs = $dom->filter('abbr[title]');

        if ($abbrs->count() > 0) {
            $abbrs->each(function (Crawler $node) use ($resource) {
                $resource->addAbbreviation(
                    $node->text(),
                    $node->attr('title')
                );
            });
        }
    }
}
