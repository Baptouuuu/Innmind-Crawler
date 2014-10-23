<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;

/**
 * Extract the page title from a html page
 */
class TitlePass
{
    public function handle(ResourceEvent $event)
    {
        $resource = $event->getResource();

        if (!($resource instanceof HtmlPage)) {
            return;
        }

        $dom = $event->getDOM();

        $h1s = $dom->filter('h1');

        if ($h1s->count() === 1) {
            $resource->setTitle($h1s->text());
        } else {
            $resource->setTitle(
                $dom->filter('head title')->text()
            );
        }
    }
}