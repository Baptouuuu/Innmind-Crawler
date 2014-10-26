<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\ValidatorInterface;

/**
 * Extract the document charset
 */
class CharsetPass
{
    protected $validator;

    /**
     * Set the validator
     *
     * @param ValidatorInterface validator
     */

    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Process the crawled resource
     *
     * @param ResourceEvent $event
     */

    public function handle(ResourceEvent $event)
    {
        $resource = $event->getResource();

        if (!($resource instanceof HtmlPage)) {
            return;
        }

        $regex = new Regex(['pattern' => '/(?P<charset>charset=[a-zA-Z\-\d]+)/']);

        if (
            $resource->hasHeader('Content-Type') &&
            $this
                ->validator
                ->validate(
                    $resource->getHeader('Content-Type'),
                    $regex
                )
                ->count() === 0
        ) {
            preg_match(
                $regex->pattern,
                $resource->getHeader('Content-Type'),
                $matches
            );
            $resource->setCharset(substr($matches['charset'], 8));

            return;
        }

        $meta = $event
            ->getDOM()
            ->filter('meta[charset]');

        if ($meta->count() === 1) {
            $resource->setCharset($meta->attr('charset'));
        }
    }
}