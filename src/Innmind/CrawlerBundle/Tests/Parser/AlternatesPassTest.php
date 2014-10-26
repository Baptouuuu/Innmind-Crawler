<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\AlternatesPass;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\UriResolver;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Validator\Validation;

class AlternatesPassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new AlternatesPass;
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

    public function testSetAlternates()
    {
        $dom = new Crawler;
        $dom->addContent('<!DOCTYPE html>
            <html>
                <head>
                    <link rel="alternate" href="/fr" hreflang="fr">
                </head>
            </html>');
        $resource = new HtmlPage;
        $resource
            ->setScheme('http')
            ->setHost('innmind.io');
        $event = new ResourceEvent($resource, new Response(200), $dom);

        $this->pass->handle($event);

        $this->assertEquals($event->getResource()->getAlternates()->count(), 1);
        $this->assertTrue($event->getResource()->getAlternates()->containsKey('fr'));
        $this->assertEquals(
            $event->getResource()->getAlternates()->get('fr'),
            'http://innmind.io/fr'
        );
    }
}
