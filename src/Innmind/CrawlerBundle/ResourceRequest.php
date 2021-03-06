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

    /**
     * URI where to publish the content
     * @var string
     */

    protected $publisher;

    /**
     * Authentication associated to the resource
     * @var string
     */

    protected $token;

    /**
     * The resource uuid
     * if set, it means the resource has already been crawled before
     * @var string
     */

    protected $uuid;

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

    /**
     * Set the URI where to publish the resource
     *
     * @param string $uri
     *
     * @return ResourceRequest self
     */

    public function setPublisherURI($uri)
    {
        $this->publisher = (string) $uri;

        return $this;
    }

    /**
     * Check if a publication uri is set
     *
     * @return bool
     */

    public function hasPublisherURI()
    {
        return (bool) $this->publisher;
    }

    /**
     * Return the URI where to publish the resource
     *
     * @return string
     */

    public function getPublisherURI()
    {
        return $this->publisher;
    }

    /**
     * Set the authentication token
     *
     * @param string $token
     *
     * @return ResourceRequest self
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

    /**
     * Set the resource uuid
     *
     * @param string $uuid
     *
     * @return ResourceRequest self
     */

    public function setUUID($uuid)
    {
        $this->uuid = (string) $uuid;

        return $this;
    }

    /**
     * Check if the resource has a uuid
     *
     * @return bool
     */

    public function hasUUID()
    {
        return (bool) $this->uuid;
    }

    /**
     * Return the resource uuid
     *
     * @return string
     */

    public function getUUID()
    {
        return $this->uuid;
    }
}
