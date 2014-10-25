<?php

namespace Innmind\CrawlerBundle\Tests\Parser;

use Innmind\CrawlerBundle\Parser\ContentPass;
use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use GuzzleHttp\Message\Response;
use Symfony\Component\DomCrawler\Crawler;

class ContentPassTest extends \PHPUnit_Framework_TestCase
{
    protected $pass;

    public function setUp()
    {
        $this->pass = new ContentPass;
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

    public function testSetMainContent()
    {
        $dom = new Crawler;
        $dom->addContent(<<<EOF
            <!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name=viewport content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="author" content="Langlade Baptiste">
        <meta name="description" content="Innmind is a new kind of search engine, it helps you to discover new things" />
        <title>Home - Innmind</title>
        <link rel="stylesheet" href="/front/css/site.css">
        <link rel="icon" type="image/x-icon" href="/favicon.ico" />
        <link rel="icon" type="image/png" href="/favicon.png" />
    </head>
    <body>

        <header id="header">
            <div class=inner>
                <div class=logo>
                    <a href="http://innmind.io/">
                        <span>Inn</span><span>mind</span>
                    </a>
                </div>
            </div>
        </header>

        <section id="content">
            <p class="goal">
    This project is an attempt to build a search engine that helps you discover new things.
    The prime goal is to offer results based on your search, but instead of redirecting you
    to the website, Innmind will also propose content related to the one you're about to reach.
</p>
<p class="use-case">
    One of the use cases could be if you heard of a subject (say ancient Egypt) and want to discover
    the culture, but you don't who where to start. Here, you would just type "ancient Egypt" and
    it would propose results (like any search engine), plus other pages related to the results
    but not necessarily related to your search per se.
</p>
<p class="open-source">
    This project is fully open-sourced on <a href="http://github.com/Baptouuuu/Innmind">Github</a>.
</p>
        </section>

        <footer id="footer">
            <div class=inner>
                <ul class="networks">
                    <li class="github">
                        <a href="http://github.com/Baptouuuu/Innmind" target="_blank">github</a>
                    </li>
                </ul>
            </div>
        </footer>
</body>
</html>
EOF
);
        $event = new ResourceEvent(new HtmlPage, new Response(200), $dom);

        $this->pass->handle($event);

        $this->assertEquals(
            $event->getResource()->getContent(),
<<<EOF
This project is an attempt to build a search engine that helps you discover new things.
    The prime goal is to offer results based on your search, but instead of redirecting you
    to the website, Innmind will also propose content related to the one you're about to reach.


    One of the use cases could be if you heard of a subject (say ancient Egypt) and want to discover
    the culture, but you don't who where to start. Here, you would just type "ancient Egypt" and
    it would propose results (like any search engine), plus other pages related to the results
    but not necessarily related to your search per se.


    This project is fully open-sourced on Github.
EOF
);
    }
}

