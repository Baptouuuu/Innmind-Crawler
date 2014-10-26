<?php

namespace Innmind\CrawlerBundle;

use Innmind\CrawlerBundle\Entity\Resource;
use Innmind\CrawlerBundle\Normalization\Normalizer;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

/**
 * Publish the crawled resource to the requested URI
 * via a HTTP POST request
 */

class Publisher
{
    /**
     * HTTP client
     * @var ClientInterface
     */

    protected $client;

    /**
     * Publisher host name
     * @var string
     */

    protected $host;

    /**
     * Normalizer
     * @var Normalizer
     */

    protected $normalizer;

    /**
     * Logger
     * @var  LoggerInterface
     */

    protected $logger;

    /**
     * Encoder
     * @var JsonEncoder
     */

    protected $encoder;

    public function __construct()
    {
        $this->client = new Client;
    }

    /**
     * Set the host name
     *
     * @param string $host
     */

    public function setHost($host)
    {
        $this->host = (string) $host;
    }

    /**
     * Set the data normalizer
     *
     * @param Normalizer $normalizer
     */

    public function setNormalizer(Normalizer $normalizer)
    {
        $this->normalizer = $normalizer;
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
     * Publish the specified resource
     *
     * @param Resource $resource
     * @param string $uri URI where to send the resource
     * @param string $token Authentication token
     */

    public function publish(Resource $resource, $uri, $token)
    {
        $data = $this->normalizer->normalize($resource);

        $this->logger->info('Resource normalized', [
            'uri' => $resource->getURI(),
            'data' => $data
        ]);

        try {

            $response = $this->client->post($uri, [
                'headers' => [
                    'Host' => $this->host,
                    'X-Token' => $token,
                    'X-Resource' => $resource->getURI()
                ],
                'body' => $data,
                'timeout' => 42
            ]);

            $this->logger->info('Resource sent to publication server', [
                'response_code' => $response->getStatusCode(),
                'response_status' => $response->getReasonPhrase()
            ]);

        } catch (\Exception $e) {
            $this->logger->error('Resource publication failed', [
                'error_code' => $e->getCode(),
                'error_message' => $e->getMessage()
            ]);
        }
    }
}
