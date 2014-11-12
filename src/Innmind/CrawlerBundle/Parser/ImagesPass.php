<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\UriResolver;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Retrieve all the images in the page
 */
class ImagesPass
{
     protected $resolver;

    /**
     * Set the uri resolver
     *
     * @param UriResolver $resolver
     */

    public function setUriResolver(UriResolver $resolver)
    {
        $this->resolver = $resolver;
    }

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
        $figures = $dom->filter('figure');

        if ($figures->count() > 0) {
            $figures->each(function (Crawler $node) use ($resource) {
                $img = $node->filter('img');
                $caption = $node->filter('figcaption');

                if ($img->count() === 0) {
                    return;
                }

                $alt = $caption->count() === 1 ?
                    $caption->text() :
                    $img->attr('alt');

                $resource->addImage(
                    $this->resolver->resolve(
                        $img->attr('src'),
                        $resource
                    ),
                    $alt
                );
            });
        }

        $imgs = $dom->filter('img');

        if ($imgs->count() > 0) {
            $imgs->each(function (Crawler $node) use ($resource) {
                $resource->addImage(
                    $this->resolver->resolve(
                        $node->attr('src'),
                        $resource
                    ),
                    $node->attr('alt')
                );
            });
        }
    }
}
