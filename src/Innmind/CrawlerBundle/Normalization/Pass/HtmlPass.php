<?php

namespace Innmind\CrawlerBundle\Normalization\Pass;

use Innmind\CrawlerBundle\Normalization\NormalizationPassInterface;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Normalization\DataSet;

class HtmlPass implements NormalizationPassInterface
{
    /**
     * {@inheritdoc}
     */

    public function normalize(Resource $resource, DataSet $dataset)
    {
        if (!($resource instanceof HtmlPage)) {
            return;
        }

        $translations = $resource->getAlternates()->toArray();

        if (!empty($translations)) {
            $dataset->set('translations', $translations);
        }

        if ($resource->hasAuthor()) {
            $dataset->set('author', $resource->getAuthor());
        }

        if ($resource->hasDescription()) {
            $dataset->set('description', $resource->getDescription());
        }

        if ($resource->hasCanonical()) {
            $dataset->set('canonical', $resource->getCanonical());
        }

        $dataset
            ->set('webapp', $resource->hasWebApp())
            ->set('title', $resource->getTitle())
            ->set('content', $resource->getContent())
            ->set('language', $resource->getLanguage());

        $links = array_unique($resource->getLinks()->toArray());

        if (!empty($links)) {
            $dataset->set('links', $links);
        }

        if ($resource->hasRSS()) {
            $dataset->set('rss', $resource->getRSS());
        }

        $dataset->set('charset', $resource->getCharset());

        if ($resource->hasAndroidURI()) {
            $dataset->set('android', $resource->getAndroidURI());
        }

        if ($resource->hasIosURI()) {
            $dataset->set('ios', $resource->getIosURI());
        }

        $dataset->set('journal', $resource->isJournal());
    }
}
