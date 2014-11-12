<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\UriResolver;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Retrieve the base url if any set
 */
class BasePass
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
        $base = $dom->filter('base[href]');

        if ($base->count() === 1) {
            $resource->setBase($base->attr('href'));
        }
    }
}
