<?php

namespace App\Infrastructure\WebSocket\Server;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        echo 'connection';
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $matches = [];

        if (preg_match('!^broadcast:(.*)$!', $msg, $matches)) {
            //$from->close();
            //print_r($matches);
            foreach ($this->clients as $client) {
                if ($from !== $client) {
                    $client->send($matches[1]);
                }
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
