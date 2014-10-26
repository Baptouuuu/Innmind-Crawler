<?php

namespace Innmind\CrawlerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Basic representation of a web resource
 */
class Resource
{
    /**
     * Resource uri
     * @var string
     */

    protected $uri;

    /**
     * URI scheme
     * @var string
     */

    protected $scheme;

    /**
     * URI host
     * @var string
     */

    protected $host;

    /**
     * URI domain
     * @var string
     */

    protected $domain;

    /**
     * URI top level domain
     * @var string
     */

    protected $tld;

    /**
     * URI port
     * @var integer
     */

    protected $port;

    /**
     * Resource path
     * @var string
     */

    protected $path = '/';

    /**
     * URI query
     * @var string
     */

    protected $query;

    /**
     * URI fragment
     * @var string
     */

    protected $fragment;

    /**
     * Resource header
     * @var ArrayCollection
     */

    protected $headers;

    /**
     * Resource status code
     * @var integer
     */

    protected $statusCode;

    public function __construct()
    {
        $this->headers = new ArrayCollection();
    }

    /**
     * Set the resource uri
     *
     * @param string $uri
     *
     * @return Resource self
     */

    public function setURI($uri)
    {
        $this->uri = (string) $uri;

        return $this;
    }

    /**
     * Return the resource uri
     *
     * @return string
     */

    public function getURI()
    {
        return $this->uri;
    }

    /**
     * Set the scheme
     *
     * @param string $scheme
     *
     * @return Resource self
     */

    public function setScheme($scheme)
    {
        $this->scheme = (string) $scheme;

        return $this;
    }

    /**
     * Return the scheme
     *
     * @return string
     */

    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Set the host
     *
     * @param string $host
     *
     * @return Resource self
     */

    public function setHost($host)
    {
        $this->host = (string) $host;

        return $this;
    }

    /**
     * Return the host
     *
     * @return string
     */

    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set the domain
     *
     * @param string $domain
     *
     * @return Resource self
     */

    public function setDomain($domain)
    {
        $this->domain = (string) $domain;

        return $this;
    }

    /**
     * Return the domain
     *
     * @return string
     */

    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set the top leve domain
     *
     * @param string $tld
     *
     * @return Resource self
     */

    public function setTopLevelDomain($tld)
    {
        $this->tld = (string) $tld;

        return $this;
    }

    /**
     * Return the top level domain
     *
     * @return string
     */

    public function getTopLevelDomain()
    {
        return $this->tld;
    }

    /**
     * Set the port
     *
     * @param integer $port
     *
     * @return Resource self
     */

    public function setPort($port)
    {
        $this->port = (int) $port;

        return $this;
    }

    /**
     * Check if the port is set
     *
     * @return bool
     */

    public function hasPort()
    {
        return is_int($this->port) && !empty($this->port);
    }

    /**
     * Return the port
     *
     * @return integer
     */

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

    /**
     * Set the path
     *
     * @param string $path
     *
     * @return Resource self
     */

    public function setPath($path)
    {
        $this->path = $path ? (string) $path : '/';

        return $this;
    }

    /**
     * Return the path
     *
     * @return string
     */

    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the query
     *
     * @param string $query
     *
     * @return Resource self
     */

    public function setQuery($query)
    {
        $this->query = (string) $query;

        return $this;
    }

    /**
     * Check if the query is set
     *
     * @return bool
     */

    public function hasQuery()
    {
        return (bool) $this->query;
    }

    /**
     * Return the query
     *
     * @return string
     */

    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set the frgament
     *
     * @param string $fragment
     *
     * @return Resource self
     */

    public function setFragment($fragment)
    {
        $this->fragment = (string) $fragment;

        return $this;
    }

    /**
     * Check if the fragment is set
     *
     * @return bool
     */

    public function hasFragment()
    {
        return (bool) $this->fragment;
    }

    /**
     * Return the fragment
     *
     * @return string
     */

    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * Add an http response header
     *
     * @param string $key
     * @param string|array $value
     *
     * @return Resource self
     */

    public function addHeader($key, $value)
    {
        $value = is_array($value) && count($value) === 1 ? $value[0] : $value;

        $this->headers->set($key, $value);

        return $this;
    }

    /**
     * Check if a header is set
     *
     * @param string $key
     *
     * @return bool
     */

    public function hasHeader($key)
    {
        return $this->headers->containsKey($key);
    }

    /**
     * Return a header value
     *
     * @return string|array
     */

    public function getHeader($key)
    {
        return $this->headers->get($key);
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
     * Set the status code
     *
     * @param integer $code
     *
     * @return Resource self
     */

    public function setStatusCode($code)
    {
        $this->statusCode = (int) $code;

        return $this;
    }

    /**
     * Return the status code
     *
     * @return integer
     */

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}