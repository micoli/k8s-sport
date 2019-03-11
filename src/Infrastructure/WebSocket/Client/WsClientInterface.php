<?php

namespace App\Infrastructure\WebSocket\Client;

interface WsClientInterface
{
    public function send($url);

    public function close();
}
