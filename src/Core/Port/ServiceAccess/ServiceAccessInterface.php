<?php

namespace App\Core\Port\ServiceAccess;

interface ServiceAccessInterface
{
    public function get($method);

    public function put($method, $payload);

    public function post($method, $payload);
}
