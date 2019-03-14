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

    public function get($service, $method)
    {
        return $this->send('GET', $service, $method, null);
    }

    private function send($verb, $service, $method, $payload)
    {
        $url = sprintf('http://%s/%s', $service, $method);
        try {
            $client = new Client([
                'connect_timeout' => 3,
            ]);
            if (null === $payload) {
                $response = $client->request($verb, $url);
            } else {
                $response = $client->request($verb, $url, ['json' => $payload]);
            }

            return json_decode($response->getBody()->getContents());
        } catch (RequestException $e) {
            $response = $e->getResponse();
            //$this->logger->error(sprintf("%s@%s, %s (%s) %s\n", $verb, $url, $e->getMessage(), json_encode($payload), isset($response) ? $response->getBody()->getContents() : '-'));
            $this->logger->error(sprintf("%s@%s, %s (%s) %s\n", $verb, $url, $e->getMessage(), json_encode($payload), '-'));

            return null;
        }
    }

    public function put($service, $method, $payload)
    {
        return $this->send('PUT', $service, $method, $payload);
    }

    public function post($service, $method, $payload)
    {
        return $this->send('POST', $service, $method, $payload);
    }
}
