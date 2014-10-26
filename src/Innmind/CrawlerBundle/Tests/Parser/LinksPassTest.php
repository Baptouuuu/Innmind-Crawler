<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\LinksPass;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\UriResolver;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Validator\Validation;

class LinksPassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new LinksPass;
        $resolver = new UriResolver;
        $resolver->setValidator(Validation::createValidator());
        $this->pass->setUriResolver($resolver);
    }

    public function testDoesNotHandleIfNotHtmlPage()
    {
        $event = new ResourceEvent(new Resource, new Response(200), new Crawler);

        try {
            $this->pass->handle($event);
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->assertTrue(false, 'Should not throw if handling raw resource');
        }
    }

    public function testSetLinks()
    {
        $dom = new Crawler;
        $dom->addContent('<!DOCTYPE html>
            <html>
                <head>
                    <link rel="first" href="http://innmind.io/page">
                    <link rel="next" href="http://innmind.io/page?page=4">
                    <link rel="previous" href="http://innmind.io/page?page=2">
                    <link rel="last" href="http://innmind.io/page?page=147">
                </head>
                <body>
                    <a href="#foo"></a>
                    <a href="foo"></a>
                    <a href="/foo"></a>
                    <a href="http://google.com"></a>
                </body>
            </html>');
        $resource = new HtmlPage();
        $resource
            ->setURI('http://innmind.io/bar?foo#baz')
            ->setScheme('http')
            ->setHost('innmind.io')
            ->setDomain('innmind')
            ->setTopLevelDomain('io')
            ->setPort(80)
            ->setPath('/bar')
            ->setQuery('foo')
            ->setFragment('baz');
        $event = new ResourceEvent($resource, new Response(200), $dom);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getLinks()->count(), 8);
        $this->assertEquals($event->getResource()->getLinks()->toArray(), [
            'http://innmind.io/page',
            'http://innmind.io/page?page=4',
            'http://innmind.io/page?page=2',
            'http://innmind.io/page?page=147',
            'http://innmind.io/bar?foo#foo',
            'http://innmind.io/foo',
            'http://innmind.io/foo',
            'http://google.com'
        ]);

        $resource
            ->setURI('http://innmind.io/bar/?foo#baz')
            ->setPath('/bar/')
            ->getLinks()
            ->clear();

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getLinks()->count(), 8);
        $this->assertEquals($event->getResource()->getLinks()->toArray(), [
            'http://innmind.io/page',
            'http://innmind.io/page?page=4',
            'http://innmind.io/page?page=2',
            'http://innmind.io/page?page=147',
            'http://innmind.io/bar/?foo#foo',
            'http://innmind.io/bar/foo',
            'http://innmind.io/foo',
            'http://google.com'
        ]);
    }
}
