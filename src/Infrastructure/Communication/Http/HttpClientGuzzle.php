<?php

namespace App\Infrastructure\Communication\Http;

use App\Core\Port\ServiceAccess\ServiceAccessInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

final class HttpClientGuzzle implements ServiceAccessInterface
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function get($method)
    {
        return $this->send('GET', $method, null);
    }

    private function send($method, $url, $payload)
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
            $response = $e->getResponse();
            $this->logger->error(sprintf("%s@%s, %s (%s) %s\n", $method, $url, $e->getMessage(), json_encode($payload), isset($response) ? $response->getBody()->getContents() : '-'));

            return null;
        }
    }

    public function put($method, $payload)
    {
        return $this->send('PUT', $method, $payload);
    }

    public function post($method, $payload)
    {
        return $this->send('POST', $method, $payload);
    }
}
