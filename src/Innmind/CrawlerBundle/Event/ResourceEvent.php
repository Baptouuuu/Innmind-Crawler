<?php

namespace Innmind\CrawlerBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use GuzzleHttp\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Innmind\CrawlerBundle\Entity\Resource;

/**
 * Event dispatched when a resource has been crawled and then processed
 */

class ResourceEvent extends Event
{
    protected $resource;
    protected $response;
    protected $dom;
    protected $publisher;
    protected $token;

    /**
     * Constructor
     *
     * @param Resource          $resource
     * @param ResponseInterface $response
     * @param DomCrawler        $dom
     */

    public function __construct(Resource $resource, ResponseInterface $response, DomCrawler $dom)
    {
        $this->resource = $resource;
        $this->response = $response;
        $this->dom = $dom;
    }

    /**
     * Return the resource representation object
     *
     * @return Resource
     */

    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Return the http response
     *
     * @return ResponseInterface
     */

    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Return the dom crawler
     *
     * @return Crawler
     */

    public function getDOM()
    {
        return $this->dom;
    }

    /**
     * Set the URI where to publish the processed resource
     *
     * @param string $uri
     *
     * @return ResourceEvent self
     */

    public function setPublisherURI($uri)
    {
        $this->publisher = (string) $uri;

        return $this;
    }

    /**
     * Return the URI where to send processed resource
     *
     * @return string
     */

    public function getPublisherURI()
    {
        return $this->publisher;
    }

    /**
     * Set a token used to authentify the resource
     *
     * @param string $token
     *
     * @return ResourceEvent self
     */

    public function setToken($token)
    {
        $this->token = (string) $token;

        return $this;
    }

    /**
     * Return the authentication token
     *
     * @return string
     */

    public function getToken()
    {
        return $this->token;
    }
}
