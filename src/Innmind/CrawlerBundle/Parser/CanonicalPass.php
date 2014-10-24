<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\ValidatorInterface;

/**
 * Retrieve canonical url (if any)
 */
class CanonicalPass
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

        $dom = $event->getDOM();
        $canonical = $dom->filter('link[rel="canonical"][href]');

        if ($canonical->count() === 1) {
            $canonical = $canonical->attr('href');

            $urlConstraint = new Url();

            if ($this->validator->validate($canonical, $urlConstraint)->count() > 0) {
                $url = '';
                $url .= $resource->getScheme();
                $url .= '://';
                $url .= $resource->getHost();
                $url .= !$resource->hasOptionalPort() ? ':' . (string) $resource->getPort() : '';
                $url .= $canonical;
            } else {
                $url = $canonical;
            }

            $resource->setCanonical($url);
        }
    }
}
