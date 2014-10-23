<?php

namespace Innmind\CrawlerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class HtmlPage extends Resource
{
    protected $alternates;
    protected $author;
    protected $description;
    protected $canonical;
    protected $webapp = false;
    protected $title;
    protected $content;
    /** @var links found in the html */
    protected $links;
    protected $language;
    protected $rss;

    public function __construct()
    {
        parent::__construct();

        $this->alternates = new ArrayCollection();
        $this->links = new ArrayCollection();
    }

    public function addAlternate($lang, $uri)
    {
        $this->alternates->set($lang, (string) $uri);

        return $this;
    }

    public function getAlternates()
    {
        return $this->alternates;
    }

    public function setAuthor($author)
    {
        $this->author = (string) $author;

        return $this;
    }

    public function hasAuthor()
    {
        return (bool) $this->author;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setDescription($desc)
    {
        $this->description = (string) $desc;

        return $this;
    }

    public function hasDescription()
    {
        return (bool) $this->description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setCanonical($uri)
    {
        $this->canonical = (string) $uri;

        return $this;
    }

    public function hasCanonical()
    {
        return (bool) $this->canonical;
    }

    public function getCanonical()
    {
        return $this->canonical;
    }

    public function setHasWebApp()
    {
        $this->webapp = true;

        return $this;
    }

    public function hasWebApp()
    {
        return $this->webapp;
    }

    public function setTitle($title)
    {
        $this->title = trim((string) $title);

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setContent($content)
    {
        $this->content = trim($content);

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function addLink($link)
    {
        $this->links->add($link);

        return $this;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setRSS($feed)
    {
        $this->rss = (string) $feed;

        return $this;
    }

    public function hasRSS()
    {
        return (bool) $this->rss;
    }

    public function getRSS()
    {
        return $this->rss;
    }
}