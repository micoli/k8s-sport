<?php

namespace App\Entities;

use App\Infrastructure\HttpClientInterface;
use App\Infrastructure\Data;

class BallFactory
{
    var $httpClient = null;
    var $data = null;
    var $stadium = null;

    public function __construct(HttpClientInterface $httpClient, Data $data)
    {
        $this->httpClient = $httpClient;
        $this->data = $data;
    }

    public function get()
    {
        return new Ball($this->httpClient, $this->data, new Dimension(80,100));
    }
}
