<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;

/**
 * Retrieve links to external resources
 */
class LinksPass
{
    protected $validator;

    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function handle(ResourceEvent $event)
    {
        $resource = $event->getResource();

        if (!($resource instanceof HtmlPage)) {
            return;
        }

        $validator = $this->validator;

        $event
            ->getDOM()
            ->filter('
                a[href],
                link[rel="first"][href],
                link[rel="next"][href],
                link[rel="previous"][href],
                link[rel="last"][href]
            ')
            ->each(function ($node) use ($resource, $validator) {
                $href = $node->attr('href');
                $fragmentConstraint = new Regex(['pattern' => '/^#.*$/']);
                $pathConstraint = new Regex(['pattern' => '/^\/.*$/']);

                if ($validator->validate($href, $fragmentConstraint)->count() === 0) {
                    $link = $resource->getScheme() . '://' . $resource->getHost();
                    $link .= !$resource->hasOptionalPort() ? ':' . (string) $resource->getPort() : '';
                    $link .= $resource->getPath() . '?' . $resource->getQuery();
                    $link .= $href;
                } else if ($validator->validate($href, $pathConstraint)->count() === 0) {
                    $link = $resource->getScheme() . '://' . $resource->getHost();
                    $link .= !$resource->hasOptionalPort() ? ':' . (string) $resource->getPort() : '';
                    $link .= $href;
                } else if (
                    $validator->validate($href, new Url())->count() > 0 &&
                    substr($href, 0, 1) !== '/'
                ) {
                    $link = $resource->getScheme() . '://' . $resource->getHost();
                    $link .= !$resource->hasOptionalPort() ? ':' . (string) $resource->getPort() : '';

                    if (substr($link, -1) === '/') {
                        $link .= $resource->getPath() . $href;
                    } else {
                        $parts = explode('/', $resource->getPath());
                        array_pop($parts);
                        $parts[] = $href;
                        $link .= implode('/', $parts);
                    }
                } else {
                    $link = $href;
                }

                $resource->addLink($link);
            });
    }
}
