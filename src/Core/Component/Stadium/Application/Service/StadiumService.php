<?php

namespace App\Core\Component\Stadium\Application\Service;

use App\Core\Component\Stadium\Domain\Stadium;
use App\Infrastructure\Communication\Http\HttpClientInterface;
use App\Infrastructure\WebSocket\Client\WsClientInterface;
use Psr\Log\LoggerInterface;

class StadiumService
{
    /** @var HttpClientInterface */
    private $httpClient;

    public function __construct(LoggerInterface $logger, WsClientInterface $WsClient, HttpClientInterface $httpClient)
    {
        $this->logger = $logger;
        $this->WsClient = $WsClient;
        $this->httpClient = $httpClient;
    }

    public function run(Stadium $stadium)
    {
        //$this->WsClient->send('broadcast:'.$ball->serialize());
    }
}
