<?php

namespace Innmind\CrawlerBundle\Tests\Entity;

use Innmind\CrawlerBundle\Entity\HtmlPage;
use Doctrine\Common\Collections\ArrayCollection;

class HtmlPageTest extends \PHPUnit_Framework_TestCase
{
    public function testAddAlternate()
    {
        $p = new HtmlPage();

        $this->assertEquals($p->getAlternates()->count(), 0);
        $this->assertEquals($p->addAlternate('en', 'http://localhost/en'), $p);
        $this->assertEquals($p->getAlternates()->count(), 1);
    }

    public function testSetAuthor()
    {
        $p = new HtmlPage();

        $this->assertEquals($p->hasAuthor(), false);
        $this->assertEquals($p->setAuthor('myself'), $p);
        $this->assertEquals($p->hasAuthor(), true);
        $this->assertEquals($p->getAuthor(), 'myself');
    }

    public function testSetDescription()
    {
        $p = new HtmlPage();

        $this->assertEquals($p->hasDescription(), false);
        $this->assertEquals($p->setDescription('some content'), $p);
        $this->assertEquals($p->hasDescription(), true);
        $this->assertEquals($p->getDescription(), 'some content');
    }

    public function testSetCanonical()
    {
        $p = new HtmlPage();

        $this->assertEquals($p->hasCanonical(), false);
        $this->assertEquals($p->setCanonical('http://localhost/'), $p);
        $this->assertEquals($p->hasCanonical(), true);
        $this->assertEquals($p->getCanonical(), 'http://localhost/');
    }

    public function testSetWebApp()
    {
        $p = new HtmlPage();

        $this->assertEquals($p->hasWebApp(), false);
        $this->assertEquals($p->setHasWebApp(), $p);
        $this->assertEquals($p->hasWebApp(), true);
    }

    public function testSetTitle()
    {
        $p = new HtmlPage();

        $this->assertEquals($p->getTitle(), null);
        $this->assertEquals($p->setTitle('title'), $p);
        $this->assertEquals($p->getTitle(), 'title');
    }

    public function testSetContent()
    {
        $p = new HtmlPage();

        $this->assertEquals($p->getContent(), null);
        $this->assertEquals($p->setContent('Content text'), $p);
        $this->assertEquals($p->getContent(), 'Content text');
    }

    public function testAddLinks()
    {
        $p = new HtmlPage();

        $this->assertEquals($p->getLinks()->count(), 0);
        $this->assertEquals($p->addLink('http://localhost/somewhere'), $p);
        $this->assertEquals($p->getLinks()->count(), 1);
    }

    public function testSetLanguage()
    {
        $p = new HtmlPage();

        $this->assertEquals($p->getLanguage(), null);
        $this->assertEquals($p->setLanguage('fr-FR'), $p);
        $this->assertEquals($p->getLanguage(), 'fr-FR');
    }

    public function testSetRSS()
    {
        $p = new HtmlPage();

        $this->assertEquals($p->hasRSS(), false);
        $this->assertEquals($p->setRSS('http://localhost/rss'), $p);
        $this->assertEquals($p->hasRSS(), true);
        $this->assertEquals($p->getRSS(), 'http://localhost/rss');
    }

    public function testSetCharset()
    {
        $p = new HtmlPage();

        $this->assertEquals($p->getCharset(), null);
        $this->assertEquals($p->setCharset('UTF-8'), $p);
        $this->assertEquals($p->getCharset(), 'UTF-8');
    }
}
