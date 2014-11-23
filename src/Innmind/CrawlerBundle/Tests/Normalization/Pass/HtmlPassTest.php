<?php

namespace Innmind\CrawlerBundle\Tests\Normalization\Pass;

use Innmind\CrawlerBundle\Normalization\Pass\HtmlPass;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Normalization\DataSet;

class HtmlPassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new HtmlPass;
    }

    public function testSetBasicValues()
    {
        $page = new HtmlPage;
        $data = new DataSet;

        $page
            ->setTitle('Innmind')
            ->setContent('new kind of search engine')
            ->setLanguage('en')
            ->setCharset('UTF-8');

        $this->pass->normalize($page, $data);

        $this->assertEquals(
            [
                'title' => 'Innmind',
                'content' => 'new kind of search engine',
                'language' => 'en',
                'charset' => 'UTF-8',
                'journal' => false,
                'webapp' => false
            ],
            $data->getArray()
        );
    }

    public function testSetAlternate()
    {
        $page = new HtmlPage;
        $data = new DataSet;

        $page->addAlternate('fr', 'http://innmind.io/fr');

        $this->pass->normalize($page, $data);

        $this->assertTrue(isset($data->getArray()['translations']));
        $this->assertEquals(
            ['fr' => 'http://innmind.io/fr'],
            $data->getArray()['translations']
        );
    }

    public function testSetLinks()
    {
        $page = new HtmlPage;
        $data = new DataSet;

        $page
            ->addLink('http://github.com/Baptouuuu/Innmind')
            ->addLink('http://github.com/Baptouuuu/Innmind');

        $this->pass->normalize($page, $data);

        $this->assertTrue(isset($data->getArray()['links']));
        $this->assertEquals(
            ['http://github.com/Baptouuuu/Innmind'],
            $data->getArray()['links']
        );
    }

    public function testSetAuthor()
    {
        $page = new HtmlPage;
        $data = new DataSet;

        $page->setAuthor('Myself');

        $this->pass->normalize($page, $data);

        $this->assertTrue(isset($data->getArray()['author']));
        $this->assertEquals(
            'Myself',
            $data->getArray()['author']
        );
    }

    public function testSetDescription()
    {
        $page = new HtmlPage;
        $data = new DataSet;

        $page->setDescription('foo');

        $this->pass->normalize($page, $data);

        $this->assertTrue(isset($data->getArray()['description']));
        $this->assertEquals(
            'foo',
            $data->getArray()['description']
        );
    }

    public function testSetCanonical()
    {
        $page = new HtmlPage;
        $data = new DataSet;

        $page->setCanonical('foo');

        $this->pass->normalize($page, $data);

        $this->assertTrue(isset($data->getArray()['canonical']));
        $this->assertEquals(
            'foo',
            $data->getArray()['canonical']
        );
    }

    public function testSetRSS()
    {
        $page = new HtmlPage;
        $data = new DataSet;

        $page->setRSS('foo');

        $this->pass->normalize($page, $data);

        $this->assertTrue(isset($data->getArray()['rss']));
        $this->assertEquals(
            'foo',
            $data->getArray()['rss']
        );
    }

    public function testSetAndroidURI()
    {
        $page = new HtmlPage;
        $data = new DataSet;

        $page->setAndroidURI('foo');

        $this->pass->normalize($page, $data);

        $this->assertTrue(isset($data->getArray()['android']));
        $this->assertEquals(
            'foo',
            $data->getArray()['android']
        );
    }

    public function testSetIosURI()
    {
        $page = new HtmlPage;
        $data = new DataSet;

        $page->setIosURI('foo');

        $this->pass->normalize($page, $data);

        $this->assertTrue(isset($data->getArray()['ios']));
        $this->assertEquals(
            'foo',
            $data->getArray()['ios']
        );
    }

    public function testSetAbbreviations()
    {
        $page = new HtmlPage;
        $data = new DataSet;

        $page->addAbbreviation('aka', 'also known as');

        $this->pass->normalize($page, $data);

        $this->assertTrue(isset($data->getArray()['abbreviations']));
        $this->assertEquals(
            ['aka' => 'also known as'],
            $data->getArray()['abbreviations']
        );
    }

    public function testSetCitations()
    {
        $page = new HtmlPage;
        $data = new DataSet;

        $page->addCite('The Scream');

        $this->pass->normalize($page, $data);

        $this->assertTrue(isset($data->getArray()['citations']));
        $this->assertEquals(
            ['The Scream'],
            $data->getArray()['citations']
        );
    }

    public function testSetBase()
    {
        $page = new HtmlPage;
        $data = new DataSet;

        $page->setBase('http://innmind.io/');

        $this->pass->normalize($page, $data);

        $this->assertTrue(isset($data->getArray()['base']));
        $this->assertEquals(
            'http://innmind.io/',
            $data->getArray()['base']
        );
    }

    public function testSetImages()
    {
        $page = new HtmlPage;
        $data = new DataSet;

        $page->addImage('http://innmind.io/logo.png', 'Logo');

        $this->pass->normalize($page, $data);

        $this->assertTrue(isset($data->getArray()['images']));
        $this->assertEquals(
            [[
                'uri' => 'http://innmind.io/logo.png',
                'description' => 'Logo'
            ]],
            $data->getArray()['images']
        );
    }
}
