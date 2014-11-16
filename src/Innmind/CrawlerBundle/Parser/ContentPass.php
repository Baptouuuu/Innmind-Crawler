<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Extract the valuable text off of the document
 * for example the article content or wikipedia description
 */
class ContentPass
{
    protected $percents = [];
    protected $deepestChildren = [];
    protected $growth = [];
    protected $totalWords = 0;

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

        $dom = $event->getDOM();
        $article = $dom->filter('[role="article"]');

        if ($article->count() === 1) {
            $resource->setContent($article->text());
            return;
        }

        $document = $dom->filter('[role="document"]');

        if ($document->count() === 1) {
            $resource->setContent($document->text());
            return;
        }

        $main = $dom->filter('[role="main"]');

        if ($main->count() === 1) {
            $resource->setContent($main->text());
            return;
        }

        $article = $dom->filter('article');

        if ($article->count() === 1) {
            $resource->setContent($article->text());
            return;
        }

        $body = $dom->filter('body');

        $this->totalWords = str_word_count($body->text());

        if ($this->totalWords === 0) {
            return;
        }

        $this->resolvePercentages($body);

        arsort($this->percents);

        $this->resolveGrowth();

        $path = $this->resolveBestPath();
        $element = $this->resolveBestElement($body, $path);

        if ($element === null) {
            $element = $body;
        }

        $resource->setContent($element->text());
    }

    /**
     * Look for the percentages of words on each dom element
     *
     * @param  Crawler $dom
     * @param  integer $level Depth in the dom tree
     */

    protected function resolvePercentages(Crawler $dom, $level = 1)
    {
        if ($level > 90) {
            return;
        }

        if (in_array($dom->getNode(0)->tagName, ['script', 'style'], true)) {
            return;
        }

        if (str_word_count($dom->text()) === 0) {
            return;
        }

        $level++;

        $children = $dom->children();

        if ($children->count() === 0) {
            $this->deepestChildren[$dom->getNode(0)->getNodePath()] = $dom;
        }

        $children->each(function (Crawler $child) use ($level) {
            $path = $child->getNode(0)->getNodePath();

            $percent = (100 * str_word_count($child->text())) / $this->totalWords;

            if ($percent === 0) {
                return;
            }

            $this->percents[$path] = $percent;

            $this->resolvePercentages($child, $level);
        });
    }

    /**
     * Calculate the growth of words for each node
     */

    protected function resolveGrowth()
    {
        foreach ($this->deepestChildren as $child) {
            $path = $child->getNode(0)->getNodePath();
            $parentPath = $child->parents()->getNode(0)->getNodePath();

            $percent = $this->percents[$path];
            $parentPercent = $parentPath === '/html/body' ?
                100 :
                $this->percents[$parentPath];

            $growth = 100 - ((100 * $percent) / $parentPercent);

            if (
                (
                    isset($this->growth[$parentPath]) &&
                    $this->growth[$parentPath] < $growth
                ) ||
                !isset($this->growth[$parentPath])
            ) {
                $this->growth[$parentPath] = $growth;
            }
        }

        arsort($this->growth);
    }

    /**
     * Resolve element having best scores in word count and word growth
     *
     * @return string
     */

    protected function resolveBestPath()
    {
        $growths = $this->growth;
        $bestPath = array_keys($growths)[0];
        $bestScore = array_values($growths)[0] + $this->percents[$bestPath];
        array_shift($growths);

        foreach ($growths as $path => $growth) {
            $score = $growth + $this->percents[$path];

            if ($score > $bestScore) {
                $bestPath = $path;
            }
        }

        return $bestPath;
    }

    /**
     * Extract the dom crawler for the given XPath
     *
     * @param Crawler $dom
     * @param string $path XPath
     *
     * @return Crawler
     */

    protected function resolveBestElement(Crawler $dom, $path)
    {
        $bestElement = null;

        $dom
            ->children()
            ->each(function (Crawler $child) use (&$bestElement, $path) {
                if ($child->getNode(0)->getNodePath() === $path) {
                    $bestElement = $child;
                } else if ($bestElement === null) {
                    $bestElement = $this->resolveBestElement($child, $path);
                }
            });

        return $bestElement;
    }
}
