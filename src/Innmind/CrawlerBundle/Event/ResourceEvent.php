<?php

namespace Innmind\CrawlerBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use GuzzleHttp\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Innmind\CrawlerBundle\Entity\Resource;

class ResourceEvent extends Event
{
    protected $resource;
    protected $response;
    protected $dom;

    public function __construct(Resource $resource, ResponseInterface $response, DomCrawler $dom)
    {
        $this->resource = $resource;
        $this->response = $response;
        $this->dom = $dom;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getDOM()
    {
        return $this->dom;
    }
}
