<?php

namespace App\Core\Port\ServiceAccess;

interface ServiceAccessInterface
{
    public function send($method, $url, $payload);
}
