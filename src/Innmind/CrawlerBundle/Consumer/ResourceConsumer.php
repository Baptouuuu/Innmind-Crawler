<?php

namespace Innmind\CrawlerBundle\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Innmind\CrawlerBundle\ResourceRequest;
use Innmind\CrawlerBundle\Crawler;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Exception\ClientException;

class ResourceConsumer implements ConsumerInterface
{
    protected $crawler;
    protected $logger;

    /**
     * Set the crawler service
     *
     * @param Crawler $crawler
     */

    public function setCrawler(Crawler $crawler)
    {
        $this->crawler = $crawler;
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

    public function execute(AMQPMessage $msg)
    {
        try {
            $data = unserialize($msg->body);

            $request = new ResourceRequest;
            $request
                ->setURI($data['uri'])
                ->setPublisherURI($data['publisher'])
                ->setToken($data['token']);

            if (isset($data['language'])) {
                $request->addHeader('Accept-Language', $data['language']);
            }

            if (isset($data['referer'])) {
                $request->addHeader('Referer', $data['referer']);
            }

            if (isset($data['uuid'])) {
                $request->setUUID($data['uuid']);
            }

            $this->crawler->crawl($request);
            $this->logger->info('Acknowledging message is processed');
            return true;
        } catch (\Exception $e) {
            $this->logger->error('Failed to crawl resource', [
                'uri' => $data['uri'],
                'errorMessage' => $e->getMessage(),
                'errorCode' => $e->getCode(),
            ]);

            if ($e instanceof ClientException) {
                $this->logger->info('4xx http error acknowledged as processed');
                return true;
            }

            $this->logger->info('Acknowledging message is not processed (despite the error)');
            return false;
        }
    }
}
