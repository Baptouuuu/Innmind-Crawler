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

        $response = $this->client->send($req);
        $this->logger->info('Resoure crawled', ['uri' => $request->getURI()]);

        $dom = new DomCrawler();
        $dom->addContent((string) $response->getBody());

        $resource = $this->factory->make($response->getHeader('Content-Type'));

        $resource->setURI($request->getURI());
        $resource->setStatusCode($response->getStatusCode());

        $event = new ResourceEvent($resource, $response, $dom);
        $this->dispatcher->dispatch(
            ResourceEvents::CRAWLED,
            $event
        );

        $this->logger->info('Resource processed', [
            'uri' => $request->getURI(),
            'statusCode' => $resource->getStatusCode()
        ]);
        $errors = $this->validator->validate($resource);

        if ($errors->count() > 0) {
            $this->logger->critical('Resource processing resulted with errors', [
                'uri' => $request->getURI(),
                'errors' => $errors
            ]);
            throw new RuntimeException('Invalid resource');
        }

        $this->dispatcher->dispatch(
            ResourceEvents::PROCESSED,
            $event
        );
        $this->logger->info('Resource fully processed', ['uri' => $request->getURI()]);

        return $resource;
    }
}