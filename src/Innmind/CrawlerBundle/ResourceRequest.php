<?php

namespace Innmind\CrawlerBundle;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Used to tell the crawler which uri to crawl
 * with a set of specified headers (if any)
 */

class ResourceRequest
{
    /**
     * URI to be crawled
     * @var string
     */

    protected $uri;

    /**
     * Request headers
     * @var ArrayCollection
     */

    protected $headers;

    public function __construct()
    {
        $this->headers = new ArrayCollection();
    }

    /**
     * Set the URI to be crawled
     *
     * @param string $uri
     *
     * @return ResourceRequest self
     */

    public function setURI($uri)
    {
        $this->uri = (string) $uri;

        return $this;
    }

    /**
     * Return the URI
     *
     * @return string
     */

    public function getURI()
    {
        return $this->uri;
    }

    /**
     * Add a request header
     *
     * @param string $key
     * @param string $value
     *
     * @return ResourceRequest self
     */

    public function addHeader($key, $value)
    {
        $this->headers->set($key, $value);

        return $this;
    }

    /**
     * Add a set of headers at once
     *
     * @param array $headers
     *
     * @return ResourceRequest self
     */

    public function addHeaders(array $headers)
    {
        foreach ($headers as $key => $value) {
            $this->addHeader($key, $value);
        }

        return $this;
    }

    /**
     * Return all the headers
     *
     * @return ArrayCollection
     */

    public function getHeaders()
    {
        return $this->headers;
    }
}