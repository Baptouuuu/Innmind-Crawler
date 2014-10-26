<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;

/**
 * Extract the document language either from response header
 * or try to find it in the dom
 */
class LanguagePass
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

        $header = $event->getResponse()->getHeader('Content-Language');

        if ($header) {
            $lang = $header;
        }

        $dom = $event->getDOM();
        $html = $dom->filter('html');

        if (
            !isset($lang) &&
            $html &&
            $html->count() > 0 &&
            $html->attr('lang')
        ) {
            $lang = $html->attr('lang');
        }

        $meta = $dom->filter('meta[http-equiv="Content-Language"][content]');

        if (
            !isset($lang) &&
            $meta &&
            $meta->count() > 0 &&
            $meta->attr('content')
        ) {
            $lang = $meta->attr('content');
        }

        if (isset($lang)) {
            list($lang) = explode(',', $lang);
            $resource->setLanguage($lang);
        }
    }
}
