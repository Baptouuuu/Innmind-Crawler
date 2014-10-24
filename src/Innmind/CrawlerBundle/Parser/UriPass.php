<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Pdp\Parser;

/**
 * Extract informations off the resource uri
 */
class UriPass
{
    protected $parser;

    public function setDomainParser(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function handle(ResourceEvent $event)
    {
        $resource = $event->getResource();

        $url = $this->parser->parseUrl($resource->getURI());

        $resource
            ->setScheme($url->scheme)
            ->setHost($url->host->host)
            ->setDomain($url->host->registerableDomain)
            ->setTopLevelDomain($url->host->publicSuffix)
            ->setPort($url->port)
            ->setPath($url->path)
            ->setQuery($url->query)
            ->setFragment($url->fragment);

        $headers = $event->getResponse()->getHeaders();

        foreach ($headers as $header => $value) {
            $resource->addHeader($header, $value);
        }
    }
}
