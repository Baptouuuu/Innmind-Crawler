<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\ImagesPass;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\UriResolver;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Validator\Validation;

class ImagesPassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new ImagesPass;
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

    public function testAddImages()
    {
        $dom = new Crawler;
        $dom->addContent('<!DOCTYPE html>
            <html>
                <body>
                    <figure>
                        <img src="logo.png" alt="foo" />
                        <figcaption>Logo</figcaption>
                    </figure>
                    <figure>
                        <img src="logo2.png" alt="Logo" />
                    </figure>
                    <img src="logo.png" alt="bar" />
                    <img src="logo3.png" alt="Logo 3" />
                </body>
            </html>');
        $resource = new HtmlPage;
        $resource
            ->setScheme('http')
            ->setHost('innmind.io')
            ->setPort(80);
        $event = new ResourceEvent($resource, new Response(200), $dom);

        $this->assertEquals(0, $event->getResource()->getImages()->count());

        $this->pass->handle($event);

        $this->assertEquals(3, $event->getResource()->getImages()->count());
        $this->assertEquals(
            [
                'http://innmind.io/logo.png' => 'Logo',
                'http://innmind.io/logo2.png' => 'Logo',
                'http://innmind.io/logo3.png' => 'Logo 3',
            ],
            $event->getResource()->getImages()->toArray()
        );
    }
}
