<?php

namespace App\Entities;

use App\Infrastructure\HttpClientInterface;
use App\Infrastructure\Data;

class PlayerFactory
{
    public function __construct(HttpClientInterface $httpClient, Data $data)
    {
        $this->httpClient = $httpClient;
        $this->data = $data;
    }

    public function get(){
        return new Player($this->httpClient,$this->data);
    }
}
