<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;

class WebAppPass
{
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
