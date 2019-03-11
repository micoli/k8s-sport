<?php

namespace App\Infrastructure\WebSocket\Client;

interface WsClientInterface
{
    public function send($method);

    public function close();
}
