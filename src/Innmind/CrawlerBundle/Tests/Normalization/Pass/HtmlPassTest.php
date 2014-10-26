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
            ->addAlternate('fr', 'http://innmind.io/fr')
            ->setTitle('Innmind')
            ->setContent('new kind of search engine')
            ->addLink('http://github.com/Baptouuuu/Innmind')
            ->addLink('http://github.com/Baptouuuu/Innmind')
            ->setLanguage('en')
            ->setCharset('UTF-8');

        $this->pass->normalize($page, $data);

        $this->assertEquals(
            [
                'translations' => ['fr' => 'http://innmind.io/fr'],
                'title' => 'Innmind',
                'content' => 'new kind of search engine',
                'links' => ['http://github.com/Baptouuuu/Innmind'],
                'language' => 'en',
                'charset' => 'UTF-8',
                'journal' => false,
                'webapp' => false
            ],
            $data->getArray()
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
}
