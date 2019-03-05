<?php

namespace App\Entities;

use App\Infrastructure\HttpClientInterface;
use App\Infrastructure\Data;
use Psr\Log\LoggerInterface;

class BallFactory
{
    var $httpClient = null;
    var $data = null;
    var $stadium = null;

    public function __construct(HttpClientInterface $httpClient, Data $data, LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->data = $data;
        $this->logger = $logger;
    }

    public function get()
    {
        return new Ball($this->httpClient, $this->data, new Dimension(80,100),$this->logger);
    }
}
