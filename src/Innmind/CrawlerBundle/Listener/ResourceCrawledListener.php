<?php

namespace Innmind\CrawlerBundle\Listener;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Publisher;

/**
 * Called once the reource has been parsed
 * if a publisher URI is set, send the resource to it
 */

class ResourceCrawledListener
{
    protected $publisher;

    /**
     * Set the resource publisher
     *
     * @param Publisher $publisher
     */

    public function setPublisher(Publisher $publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * Handle the event
     *
     * @param ResourceEvent $event
     */

    public function handle(ResourceEvent $event)
    {
        $request = $event->getResourceRequest();

        if ($request->hasPublisherURI()) {
            $this->publisher->publish(
                $event->getResource(),
                $request
            );
        }
    }
}
