<?php

namespace App\Entities;

use App\Infrastructure\HttpClientInterface;
use App\Infrastructure\Data;
use Psr\Log\LoggerInterface;

class PlayerFactory
{
    public function __construct(HttpClientInterface $httpClient, Data $data, LoggerInterface $log)
    {
        $this->httpClient = $httpClient;
        $this->data = $data;
        $this->log = $log;
    }

    public function get()
    {
        return new Player($this->httpClient, $this->data, $this->log);
    }
}
