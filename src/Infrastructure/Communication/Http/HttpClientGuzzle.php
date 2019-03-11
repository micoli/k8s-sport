<?php

namespace App\Infrastructure\Communication\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

class HttpClientGuzzle implements HttpClientInterface
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function send($method, $url, $payload)
    {
        try {
            $client = new Client([
                'connect_timeout' => 3,
            ]);
            if (null === $payload) {
                $response = $client->request($method, $url);
            } else {
                $response = $client->request($method, $url, ['json' => $payload]);
            }

            return json_decode($response->getBody()->getContents());
        } catch (RequestException $e) {
            $this->logger->error(sprintf("%s@%s (%s) %s\n", $method, $url, json_encode($payload), $e->getMessage()));

            return null;
        }
    }
}
