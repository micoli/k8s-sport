<?php

namespace App\Infrastructure\Communication\Http;

interface HttpClientInterface
{
    public function send($method, $url, $payload);
}
