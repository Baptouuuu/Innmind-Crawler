<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\ValidatorInterface;

/**
 * Check if the document as an associated RSS feed
 */
class RssPass
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

        $rss = $event
            ->getDOM()
            ->filter('link[rel="alternate"][type="application/rss+xml"][href]');

        if ($rss->count() === 1) {
            $rss = $rss->attr('href');

            $urlConstraint = new Url();

            if ($this->validator->validate($rss, $urlConstraint)->count() > 0) {
                $url = $resource->getScheme();
                $url .= '://';
                $url .= $resource->getHost();
                $url .= !$resource->hasOptionalPort() ? ':' . (string) $resource->getPort() : '';
                $url .= $rss;
            } else {
                $url = $rss;
            }

            $resource->setRSS($url);
        }
    }
}
