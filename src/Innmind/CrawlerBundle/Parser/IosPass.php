<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Event\ResourceEvent;

/**
 * Check if the document has a link to an ios app
 */

class IosPass
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
            ->filter('meta[name="apple-itunes-app"][content*="app-argument="]');

        if ($meta->count() === 1) {
            preg_match('/(?P<uri>app\-argument\=.*)$/', $meta->attr('content'), $matches);

            if (isset($matches['uri'])) {
                $resource->setIosURI(
                    substr($matches['uri'], 13)
                );
            }
        }
    }
}
