<?php

namespace Innmind\CrawlerBundle;

use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Entity\Resource;

/**
 * Build appropriate resource entity based on a content type
 */
class ResourceFactory
{
    public function make($contentType)
    {
        switch (true) {
            case (bool) preg_match('/(text\html|application\/xhtml\+xml)/', strtolower($contentType)):
                $resource = new HtmlPage();
                break;

            default:
                $resource = new Resource();
                break;
        }

        return $resource;
    }
}
