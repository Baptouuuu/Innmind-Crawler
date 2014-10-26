<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Event\ResourceEvent;

/**
 * Check if the document has a link to an android app
 */

class AndroidPass
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

        $alternate = $event
            ->getDOM()
            ->filter('link[rel="alternate"][href^="android-app://"]');

        if ($alternate->count() === 1) {
            $resource->setAndroidURI($alternate->attr('href'));
        }
    }
}
