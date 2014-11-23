<?php

namespace Innmind\CrawlerBundle;

use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;

/**
 * Resolve any relative url to a full uri
 */
class UriResolver
{
    protected $validator;

    /**
     * Set the validator
     *
     * @param ValidatorInterface $validator
     */

    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Resolve a relative url to a full uri
     *
     * @param string $url
     * @param Resource $resource
     *
     * @return string
     */

    public function resolve($url, Resource $resource)
    {
        $constraint = new Url;

        if (empty($url)) {
            $url = './';
        }

        if ($this->validator->validate($url, $constraint)->count() === 0) {
            return $url;
        }

        if (substr($url, 0, 2) === '//') {
            return 'http:'.$url;
        }

        if ($resource instanceof HtmlPage && $resource->hasBase()) {
            $fromBase = $resource->getBase().$url;

            if ($this->validator->validate($fromBase, $constraint)->count() === 0) {
                return $fromBase;
            }
        }

        $constraint = new Regex(['pattern' => '/^\/.*$/']); //absolute path

        if ($this->validator->validate($url, $constraint)->count() === 0) {
            $uri = $resource->getScheme() . '://' . $resource->getHost();
            $uri .= !$resource->hasOptionalPort() ?
                ':' . (string) $resource->getPort() :
                '';
            $uri .= $url;

            return $uri;
        }

        $constraint = new Regex(['pattern' => '/^\?.*$/']); //query path

        if ($this->validator->validate($url, $constraint)->count() === 0) {
            $uri = $resource->getScheme() . '://' . $resource->getHost();
            $uri .= !$resource->hasOptionalPort() ?
                ':' . (string) $resource->getPort() :
                '';
            $uri .= $resource->getPath();
            $uri .= $url;

            return $uri;
        }

        $constraint = new Regex(['pattern' => '/^\#.*$/']); //fragment path

        if ($this->validator->validate($url, $constraint)->count() === 0) {
            $uri = $resource->getScheme() . '://' . $resource->getHost();
            $uri .= !$resource->hasOptionalPort() ?
                ':' . (string) $resource->getPort() :
                '';
            $uri .= $resource->getPath() . '?' . $resource->getQuery();
            $uri .= $url;

            return $uri;
        }

        $constraint = new Url;

        //relative path
        if (
            $this->validator->validate($url, $constraint)->count() > 0 &&
            substr($url, 0, 1) !== '/'
        ) {
            if (substr($url, 0, 2) === './') {
                $url = substr($url, 2);
            }

            $uri = $resource->getScheme() . '://' . $resource->getHost();
            $uri .= !$resource->hasOptionalPort() ?
                ':' . (string) $resource->getPort() :
                '';

            $path = $resource->getPath();

            if (empty($url)) {
                $uri .= $path;
            } else if (substr($path, -1) === '/') {
                $uri .= $path . $url;
            } else {
                $parts = explode('/', $path);
                array_pop($parts);
                $parts[] = $url;
                $uri .= implode('/', $parts);
            }

            return $uri;
        }

        //can't determine how is composed url
        return $url;
    }
}
