<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;

/**
 * Extract the author name from the html (if any)
 */
class AuthorPass
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
        $author = $dom->filter('
            meta[name="author"][content],
            meta[name="Author"][content],
            meta[name="AUTHOR"][content]
        ');

        $resource->setAuthor(
            $author->count() === 1 ?
                $author->attr('content') :
                ''
        );
    }
}
