<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;

/**
 * Determine if the document is part of a web app
 */

class WebAppPass
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
        $link = $dom->filter('link[rel="manifest"][href]');

        if ($link->count() === 1) {
            $resource->setHasWebApp();
        }
    }
}
