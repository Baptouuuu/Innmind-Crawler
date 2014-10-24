<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\CanonicalPass;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Validator\Validation;

class CanonicalPassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new CanonicalPass();
        $this->pass->setValidator(Validation::createValidator());
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

    public function testSetCanonical()
    {
        $dom = new Crawler;
        $dom->addContent('<!DOCTYPE html>
            <html>
                <head>
                    <link rel="canonical" href="/some-value">
                </head>
            </html>');
        $resource = new HtmlPage;
        $resource
            ->setScheme('http')
            ->setHost('innmind.io')
            ->setPort(80);
        $event = new ResourceEvent($resource, new Response(200), $dom);

        $this->assertFalse($event->getResource()->hasCanonical());

        $this->pass->handle($event);

        $this->assertTrue($event->getResource()->hasCanonical());
        $this->assertEquals($event->getResource()->getCanonical(), 'http://innmind.io/some-value');

        $resource->setPort(8080);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getCanonical(), 'http://innmind.io:8080/some-value');

        $dom->clear();
        $dom->addContent('<!DOCTYPE html>
            <html>
                <head>
                    <link rel="canonical" href="http://innmind.io:443/some-value">
                </head>
            </html>');

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getCanonical(), 'http://innmind.io:443/some-value');
    }
}
