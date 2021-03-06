<?php

namespace Innmind\CrawlerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Object representation of all valuable informations of an html document
 */

class HtmlPage extends Resource
{
    /**
     * Translations URIs for the current document
     * @var ArrayCollection
     */

    protected $alternates;

    /**
     * Document author name
     * @var string
     */

    protected $author;

    /**
     * Document description
     * @var string
     */

    protected $description;

    /**
     * Canonical URI for the current document
     * @var string
     */

    protected $canonical;

    /**
     * Wether the document is part of a web app
     * @var boolean
     */

    protected $webapp = false;

    /**
     * Document title
     * @var string
     */

    protected $title = '';

    /**
     * Document main content (like an a blog post)
     * @var string
     */

    protected $content;

    /**
     * All links found in the page
     * @var ArrayCollection
     */

    protected $links;

    /**
     * Language of the current document
     * @var string
     */

    protected $language;

    /**
     * RSS URI for the current document
     * @var string
     */

    protected $rss;

    /**
     * Document character set
     * @var string
     */

    protected $charset;

    /**
     * Android app URI equivalent of the current document
     * @var string
     */

    protected $androidURI;

    /**
     * iOS app URI equivalent of the current document
     * @var string
     */

    protected $iosURI;

    /**
     * All the abbrviations found in a page
     * @var ArrayCollection
     */

    protected $abbrs;

    /**
     * Is the page indicating the website is a journal
     * (meaning a proper journal or any blog)
     * @var boolean
     */

    protected $journal = false;

    /**
     * Set the base url for relatives urls in the page
     * @var string
     */

    protected $base;

    /**
     * All the citations of the page
     *
     * @see http://www.w3schools.com/tags/tag_cite.asp
     * @var ArrayCollection
     */

    protected $citations;

    /**
     * Images of the page
     * @var ArrayCollection
     */

    protected $images;
    protected $imagesUris;

    /**
     * Theme color hue
     * @var int
     */

    protected $themeColorHue;

    /**
     * Theme color saturation
     * @var int
     */

    protected $themeColorSaturation;

    /**
     * Theme color lightness
     * @var int
     */

    protected $themeColorLightness;

    public function __construct()
    {
        parent::__construct();

        $this->alternates = new ArrayCollection();
        $this->links = new ArrayCollection();
        $this->abbrs = new ArrayCollection();
        $this->citations = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->imagesUris = new ArrayCollection();
    }

    /**
     * Add a new translation
     *
     * @param string $lang
     * @param string $uri
     *
     * @return HtmlPage self
     */

    public function addAlternate($lang, $uri)
    {
        $this->alternates->set($lang, (string) $uri);

        return $this;
    }

    /**
     * Return all translations
     *
     * @return ArrayCollection
     */

    public function getAlternates()
    {
        return $this->alternates;
    }

    /**
     * Set the author name
     *
     * @param string $author
     *
     * @return HtmlPage self
     */

    public function setAuthor($author)
    {
        $this->author = utf8_encode((string) $author);

        return $this;
    }

    /**
     * Check if the document has an author
     *
     * @return bool
     */

    public function hasAuthor()
    {
        return (bool) $this->author;
    }

    /**
     * Return the author name
     *
     * @return string
     */

    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the document description
     *
     * @param string $desc
     *
     * @return HtmlPage self
     */

    public function setDescription($desc)
    {
        $desc = str_replace("\n", '', $desc);
        $this->description = utf8_encode((string) $desc);

        return $this;
    }

    /**
     * Check if the document has a description
     *
     * @return bool
     */

    public function hasDescription()
    {
        return (bool) $this->description;
    }

    /**
     * Return the document description
     *
     * @return string
     */

    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the canonical URI
     *
     * @param string $uri
     *
     * @return HtmlPage self
     */

    public function setCanonical($uri)
    {
        $this->canonical = (string) $uri;

        return $this;
    }

    /**
     * Check if the document has a canonical uri
     *
     * @return bool
     */

    public function hasCanonical()
    {
        return (bool) $this->canonical;
    }

    /**
     * Return the canonical uri
     *
     * @return string
     */

    public function getCanonical()
    {
        return $this->canonical;
    }

    /**
     * Set the document is part of a web app
     *
     * @return HtmlPage self
     */

    public function setHasWebApp()
    {
        $this->webapp = true;

        return $this;
    }

    /**
     * Check if the document is part of a web app
     *
     * @return bool
     */

    public function hasWebApp()
    {
        return $this->webapp;
    }

    /**
     * Set the document title
     *
     * @param string $title
     *
     * @return HtmlPage self
     */

    public function setTitle($title)
    {
        $title = str_replace("\n", '', $title);
        $this->title = utf8_encode(trim((string) $title));

        return $this;
    }

    /**
     * Return the document title
     *
     * @return string
     */

    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the document content
     *
     * @param string $content
     *
     * @return HtmlPage self
     */

    public function setContent($content)
    {
        $content = preg_replace('/\s{2,}/', ' ', $content);
        $this->content = utf8_encode(trim($content));

        return $this;
    }

    /**
     * Return the document content
     *
     * @return string
     */

    public function getContent()
    {
        return $this->content;
    }

    /**
     * Add a link
     *
     * @param string $link
     *
     * @return HtmlPage self
     */

    public function addLink($link)
    {
        $this->links->add($link);

        return $this;
    }

    /**
     * Return all links
     *
     * @return ArrayCollection
     */

    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Set the document language
     *
     * @param string $language
     *
     * @return HtmlPage self
     */

    public function setLanguage($language)
    {
        $this->language = trim($language);

        return $this;
    }

    /**
     * Return the document language
     *
     * @return string
     */

    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set the RSS uri
     *
     * @param string $uri
     *
     * @return HtmlPage self
     */

    public function setRSS($feed)
    {
        $this->rss = (string) $feed;

        return $this;
    }

    /**
     * Check if the document has a RSS uri
     *
     * @return bool
     */

    public function hasRSS()
    {
        return (bool) $this->rss;
    }

    /**
     * Return the RSS uri
     *
     * @return string
     */

    public function getRSS()
    {
        return $this->rss;
    }

    /**
     * Set the document character set
     *
     * @param string $charset
     *
     * @return HtmlPage self
     */

    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * Return the document character set
     *
     * @return string
     */

    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Set the android uri
     *
     * @param string $uri
     *
     * @return HtmlPage self
     */

    public function setAndroidURI($uri)
    {
        $this->androidURI = (string) $uri;

        return $this;
    }

    /**
     * Check if there's an android uri
     *
     * @return bool
     */

    public function hasAndroidURI()
    {
        return (bool) $this->androidURI;
    }

    /**
     * Return the android uri
     *
     * @return string
     */

    public function getAndroidURI()
    {
        return $this->androidURI;
    }

    /**
     * Set the ios uri
     *
     * @param string $uri
     *
     * @return HtmlPage self
     */

    public function setIosURI($uri)
    {
        $this->iosURI = (string) $uri;

        return $this;
    }

    /**
     * Check if there's an ios uri
     *
     * @return bool
     */

    public function hasIosURI()
    {
        return (bool) $this->iosURI;
    }

    /**
     * Return the ios uri
     *
     * @return string
     */

    public function getIosURI()
    {
        return $this->iosURI;
    }

    /**
     * Set the document as a journal
     *
     * @return HtmlPage self
     */

    public function setJournal()
    {
        $this->journal = true;

        return $this;
    }

    /**
     * Check if the document is a journal
     *
     * @return bool
     */

    public function isJournal()
    {
        return $this->journal;
    }

    /**
     * Add a new abbreviation
     *
     * @param string $abbr
     * @param string $description
     *
     * @return HtmlPage self
     */

    public function addAbbreviation($abbr, $description)
    {
        $this->abbrs->set((string) $abbr, utf8_encode((string) $description));

        return $this;
    }

    /**
     * Return all the abbreviations
     *
     * @return ArrayCollection
     */

    public function getAbbreviations()
    {
        return $this->abbrs;
    }

    /**
     * Set the base url
     *
     * @param string $url
     *
     * @return HtmlPage self
     */

    public function setBase($url)
    {
        $this->base = (string) $url;

        return $this;
    }

    /**
     * Check if the resource has a base url
     *
     * @return bool
     */

    public function hasBase()
    {
        return !empty($this->base);
    }

    /**
     * Return the base url
     *
     * @return string
     */

    public function getBase()
    {
        return $this->base;
    }

    /**
     * Add a citation
     *
     * @param string $cite
     *
     * @return HtmlPage self
     */

    public function addCite($cite)
    {
        $this->citations->add(utf8_encode((string) $cite));

        return $this;
    }

    /**
     * Return all the citations
     *
     * @return ArrayCollection
     */

    public function getCitations()
    {
        return $this->citations;
    }

    /**
     * Add a new image
     *
     * @param string $uri
     * @param string $description
     *
     * @return HtmlPage self
     */

    public function addImage($uri, $description)
    {
        if (!$this->imagesUris->contains((string) $uri)) {
            $this->images->add([
                'uri' => (string) $uri,
                'description' => utf8_encode((string) $description),
            ]);
            $this->imagesUris->add((string) $uri);
        }

        return $this;
    }

    /**
     * Return all the images
     *
     * @return ArrayCollection
     */

    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set the theme color
     *
     * @param int $hue
     * @param int $saturation
     * @param int $lightness
     *
     * @return HtmlPage self
     */

    public function setThemeColor($hue, $saturation, $lightness)
    {
        $this->themeColorHue = (int) $hue;
        $this->themeColorSaturation = (int) $saturation;
        $this->themeColorLightness = (int) $lightness;

        return $this;
    }

    /**
     * Check if the page has a theme color
     *
     * @return bool
     */

    public function hasThemeColor()
    {
        return is_int($this->themeColorHue) &&
            is_int($this->themeColorSaturation) &&
            is_int($this->themeColorLightness);
    }

    /**
     * Return theme color hue
     *
     * @return int
     */

    public function getThemeColorHue()
    {
        return $this->themeColorHue;
    }

    /**
     * Return theme color saturation
     *
     * @return int
     */

    public function getThemeColorSaturation()
    {
        return $this->themeColorSaturation;
    }

    /**
     * Return theme color lightness
     *
     * @return int
     */

    public function getThemeColorLightness()
    {
        return $this->themeColorLightness;
    }
}
