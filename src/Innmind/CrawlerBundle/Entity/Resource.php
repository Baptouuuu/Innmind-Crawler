<?php

namespace Innmind\CrawlerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Basic representation of a web resource
 */
class Resource
{
    protected $uri;
    protected $scheme;
    protected $host;
    protected $domain;
    protected $tld;
    protected $port;
    protected $path = '/';
    protected $query;
    protected $fragment;
    protected $headers;
    protected $statusCode;

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

    public function setScheme($scheme)
    {
        $this->scheme = (string) $scheme;

        return $this;
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function setHost($host)
    {
        $this->host = (string) $host;

        return $this;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setDomain($domain)
    {
        $this->domain = (string) $domain;

        return $this;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setTopLevelDomain($tld)
    {
        $this->tld = (string) $tld;

        return $this;
    }

    public function getTopLevelDomain()
    {
        return $this->tld;
    }

    public function setPort($port)
    {
        $this->port = (int) $port;

        return $this;
    }

    public function hasPort()
    {
        return is_int($this->port) && !empty($this->port);
    }

    public function getPort()
    {
        return $this->port;
    }

    /**
     * If the port is 80 on http sheme or 443 on https scheme
     * the port can be omitted from the url
     *
     * @return bool
     */

    public function hasOptionalPort()
    {
        if (
            ($this->scheme === 'http' && $this->port === 80) ||
            ($this->scheme === 'https' && $this->port === 443) ||
            !$this->hasPort()
        ) {
            return true;
        }

        return false;
    }

    public function setPath($path)
    {
        $this->path = $path ? (string) $path : '/';

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setQuery($query)
    {
        $this->query = (string) $query;

        return $this;
    }

    public function hasQuery()
    {
        return (bool) $this->query;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setFragment($fragment)
    {
        $this->fragment = (string) $fragment;

        return $this;
    }

    public function hasFragment()
    {
        return (bool) $this->fragment;
    }

    public function getFragment()
    {
        return $this->fragment;
    }

    public function addHeader($key, $value)
    {
        $value = is_array($value) && count($value) === 1 ? $value[0] : $value;

        $this->headers->set($key, $value);

        return $this;
    }

    public function hasHeader($key)
    {
        return $this->headers->containsKey($key);
    }

    public function getHeader($key)
    {
        return $this->headers->get($key);
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setStatusCode($code)
    {
        $this->statusCode = (int) $code;

        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}