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

        if ($resource->getMime() !== 'image/jpeg') {
            return;
        }

        $exif = exif_read_data($request->getURI());

        if ($exif !== false) {
            foreach ($exif as $key => $section) {
                if (is_array($section)) {
                    foreach ($section as $name => $value) {
                        $resource->addExif($key.'.'.$name, $value);
                    }
                } else {
                    $resource->addExif($key, $section);
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
