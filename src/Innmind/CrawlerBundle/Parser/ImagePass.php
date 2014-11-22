<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\Image;

/**
 * Retrieve the base url if any set
 */
class ImagePass
{
    /**
     * Process the crawled resource
     *
     * @param ResourceEvent $event
     */

    public function handle(ResourceEvent $event)
    {
        $resource = $event->getResource();

        if (!($resource instanceof Image)) {
            return;
        }

        $request = $event->getResourceRequest();
        $response = $event->getResponse();

        $size = getimagesize($request->getURI());

        $resource
            ->setWidth($size[0])
            ->setHeight($size[1])
            ->setMime(image_type_to_mime_type($size[2]))
            ->setExtension(image_type_to_extension($size[2]));

        if ($response->hasHeader('Content-Length')) {
            $resource->setWeight($response->getHeader('Content-Length'));
        } else {
            $body = $response->getBody();

            if ($body !== null) {
                $resource->setWeight($body->getSize());
            }
        }

        $exif = exif_read_data(urlencode($request->getURI()));

        if ($exif !== false) {
            foreach ($exif as $key => $section) {
                foreach ($section as $name => $value) {
                    $resource->addExif($section.'.'.$name, $value);
                }
            }

            if (
                $resource->getWeight() === null &&
                $resource->getExif()->containsKey('FILE.FileSize')
            ) {
                $resource->setWeight(
                    $resource->getExif()->get('FILE.FileSize')
                );
            }
        }
    }
}
