<?php

namespace Innmind\CrawlerBundle;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Used to tell the crawler which uri to crawl
 * with a set of specified headers (if any)
 */
class ResourceRequest
{
    protected $uri;
    protected $headers;

    public function __construct()
    {
        $this->headers = new ArrayCollection();
    }

    public function setURI($uri)
    {
        $this->uri = (string) $uri;

        return $this;
    }

    public function getURI()
    {
        return $this->uri;
    }

    public function addHeader($key, $value)
    {
        $this->headers->set($key, $value);

        return $this;
    }

    public function addHeaders(array $headers)
    {
        foreach ($headers as $key => $value) {
            $this->addHeader($key, $value);
        }

        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}