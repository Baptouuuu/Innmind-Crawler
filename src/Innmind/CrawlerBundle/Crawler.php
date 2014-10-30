<?php

namespace Innmind\CrawlerBundle;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\ValidatorInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Event\ResourceEvents;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Retrieve a resource and tell the app to process it
 */

class Crawler
{
    protected $client;
    protected $dispatcher;
    protected $factory;
    protected $validator;
    protected $logger;
    protected $stopwatch;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Set the vent dispatcher
     *
     * @param EventDispatcherInterface $dispatcher
     */

    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Set the resourcce factory
     *
     * @param ResourceFactory $factory
     */

    public function setResourceFactory(ResourceFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Set the validator
     *
     * @param ValidatorInterface $validator
     */

    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Set the logger
     *
     * @param LoggerInterface $logger
     */

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Set the stopwatch
     *
     * @param Stopwatch $stopwatch
     */

    public function setStopwatch(Stopwatch $stopwatch)
    {
        $this->stopwatch = $stopwatch;
    }

    /**
     * Crawl the requested URI and tell the app to process it
     *
     * @param ResourceRequest $request
     *
     * @return Resource
     */

    public function crawl(ResourceRequest $request)
    {
        $this->logger->info('Crawling a resource', ['uri' => $request->getURI()]);
        $req = $this->client->createRequest('GET', $request->getURI());

        $request
            ->getHeaders()
            ->forAll(function ($key, $value) use ($req) {
                $req->addHeader($key, $value);
            });

        $req->addHeader('User-Agent', 'Innmind Crawler');

        $this->stopwatch->start('crawl');
        $response = $this->client->send($req);
        $crawlEvent = $this->stopwatch->stop('crawl');

        $this->logger->info('Resoure crawled', [
            'uri' => $request->getURI(),
            'duration' => $crawlEvent->getDuration(),
            'memory' => $crawlEvent->getMemory()
        ]);

        $dom = new DomCrawler();
        $dom->addContent((string) $response->getBody());

        $resource = $this->factory->make($response->getHeader('Content-Type'));

        $resource->setURI($request->getURI());
        $resource->setStatusCode($response->getStatusCode());

        $this->stopwatch->start('parsing');

        $event = new ResourceEvent($resource, $response, $dom);
        $this->dispatcher->dispatch(
            ResourceEvents::CRAWLED,
            $event
        );

        $parsingEvent = $this->stopwatch->stop('parsing');

        $this->logger->info('Resource processed', [
            'uri' => $request->getURI(),
            'statusCode' => $resource->getStatusCode(),
            'duration' => $parsingEvent->getDuration(),
            'memory' => $parsingEvent->getMemory()
        ]);
        $errors = $this->validator->validate($resource);

        if ($errors->count() > 0) {
            $this->logger->critical('Resource processing resulted with errors', [
                'uri' => $request->getURI(),
                'errors' => $errors
            ]);
            throw new RuntimeException('Invalid resource');
        }

        $event->setResourceRequest($request);

        $this->stopwatch->start('sending');

        $this->dispatcher->dispatch(
            ResourceEvents::PROCESSED,
            $event
        );

        $sendingEvent = $this->stopwatch->stop('sending');

        $this->logger->info('Resource fully processed', [
            'uri' => $request->getURI(),
            'duration' => $sendingEvent->getDuration(),
            'memory' => $sendingEvent->getMemory()
        ]);

        return $resource;
    }
}
