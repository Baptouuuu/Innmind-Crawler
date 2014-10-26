<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Event\ResourceEvent;

/**
 * Check if the document has many articles meaning
 * it could be a journal
 */

class JournalPass
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

        $articles = $event
            ->getDOM()
            ->filter('article');

        if ($articles->count() >1) {
            $resource->setJournal();
        }
    }
}
