<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;

/**
 * Retrieve translations for current resource
 */
class AlternatesPass
{
    public function handle(ResourceEvent $event)
    {
        $resource = $event->getResource();

        if (!($resource instanceof HtmlPage)) {
            return;
        }

        $dom = $event->getDOM();
        $alternates = $dom->filter('link[rel="alternate"][href][hreflang]');

        if ($alternates->count() > 0) {
            $alternates->each(function ($node) use ($resource) {
                $resource->addAlternate(
                    $node->attr('hreflang'),
                    $node->attr('href')
                );
            });
        }
    }
}
