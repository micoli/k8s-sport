<?php

namespace App\Infrastructure\WebSocket\Server;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

final class Chat implements MessageComponentInterface
{
    private $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        trigger_error("An error has occurred: {$e->getMessage()}\n", E_USER_WARNING);

        $conn->close();
    }
}
