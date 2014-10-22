<?php

namespace Innmind\CrawlerBundle;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\ValidatorInterface;
use RuntimeException;
use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Event\ResourceEvents;

class Crawler
{
    protected $client;
    protected $dispatcher;
    protected $factory;
    protected $validator;

    public function __construct(EventDispatcherInterface $dispatcher, ResourceFactory $factory, ValidatorInterface $validator)
    {
        $this->client = new Client();
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->validator = $validator;
    }

    public function crawl(ResourceRequest $request)
    {
        $req = $this->client->createRequest('GET', $request->getURI());

        $request
            ->getHeaders()
            ->forAll(function ($key, $value) use ($req) {
                $req->addHeader($key, $value);
            });

        $req->addHeader('User-Agent', 'Innmind Crawler');

        $response = $this->client->send($req);

        $dom = new DomCrawler();
        $dom->addContent((string) $response->getBody());

        $resource = $this->factory->make($response->getHeader('Content-Type'));

        $event = new ResourceEvent($resource, $response, $dom);
        $this->dispatcher->dispatch(
            ResourceEvents::CRAWLED,
            $event
        );

        $errors = $this->validator->validate($resource);

        if (count($errors) > 0) {
            throw new RuntimeException('Invalid resource');
        }

        $this->dispatcher->dispatch(
            ResourceEvents::PROCESSED,
            $event
        );

        return $resource;
    }
}