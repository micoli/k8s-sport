<?php

namespace App\Core\Port\ServiceAccess;

interface ServiceAccessInterface
{
    public function get($service, $method);

    public function put($service, $method, $payload);

    public function post($service, $method, $payload);
}
