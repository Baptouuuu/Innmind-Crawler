<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;

/**
 * Extract document description off of meta tag (if any)
 * otherwise take the first 150 characters of page content
 */
class DescriptionPass
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

        $meta = $event
            ->getDOM()
            ->filter('meta[name="description"][content]');

        if ($meta->count() === 1) {
            $desc = $meta->attr('content');
        }

        if (!isset($desc)) {
            $desc = substr($resource->getContent(), 0, 150) . '...';
        }

        $resource->setDescription($desc);
    }
}
