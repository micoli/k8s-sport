<?php

namespace App\Infrastructure\WebSocket\Server;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Ratchet\AbstractConnectionDecorator;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\WebSocket\WsServerInterface;

/**
 * A Ratchet component that wraps PSR\Log loggers tracking received and sent messages.
 */
final class MessageLogger implements MessageComponentInterface, WsServerInterface
{
    /** @var LoggerInterface */
    private $_in;

    /** @var LoggerInterface */
    private $_out;

    /** @var MessageComponentInterface */
    private $_component;

    /**
     * Counts the number of open connections.
     *
     * @var int
     */
    private $_i = 0;

    private $_connections;

    public function __construct(MessageComponentInterface $component, LoggerInterface $incoming = null, LoggerInterface $outgoing = null)
    {
        $this->_component = $component;
        $this->_connections = new \SplObjectStorage();

        if (null === $incoming) {
            $incoming = new NullLogger();
        }

        if (null === $outgoing) {
            $outgoing = new NullLogger();
        }

        $this->_in = $incoming;
        $this->_out = $outgoing;
    }

    /**
     * {@inheritdoc}
     */
    public function onOpen(ConnectionInterface $conn)
    {
        ++$this->_i;

        /* , 'id' => $conn->resourceId, 'ip' => $conn->remoteAddress    */
        $this->_in->info('onOpen', ['#open' => $this->_i]);

        $decoratedConn = new MessageLoggedConnection($conn);
        $decoratedConn->setLogger($this->_out);

        $this->_connections->attach($conn, $decoratedConn);

        $this->_component->onOpen($decoratedConn);
    }

    /**
     * {@inheritdoc}
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        /* 'from' => $from->resourceId, */
        $this->_in->info('onMsg', ['len' => strlen($msg), 'msg' => $msg]);

        $this->_component->onMessage($this->_connections[$from], $msg);
    }

    /**
     * {@inheritdoc}
     */
    public function onClose(ConnectionInterface $conn)
    {
        --$this->_i;

        /* , 'id' => $conn->resourceId */
        $this->_in->info('onClose', ['#open' => $this->_i]);

        $decorated = $this->_connections[$conn];
        $this->_connections->detach($conn);

        $this->_component->onClose($decorated);
    }

    /**
     * {@inheritdoc}
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        /* , 'id' => $conn->resourceId */
        $this->_in->error("onError: ({$e->getCode()}): {$e->getMessage()}", ['file' => $e->getFile(), 'line' => $e->getLine()]);

        $this->_component->onError($this->_connections[$conn], $e);
    }

    /**
     * {@inheritdoc}
     */
    public function getSubProtocols()
    {
        if ($this->_component instanceof WsServerInterface) {
            return $this->_component->getSubProtocols();
        } else {
            return [];
        }
    }
}

final class MessageLoggedConnection extends AbstractConnectionDecorator implements ConnectionInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private $resourceId;

    public function send($data)
    {
        $this->logger->info('send', ['to' => $this->resourceId, 'len' => strlen($data), 'msg' => $data]);

        $this->getConnection()->send($data);

        return $this;
    }

    public function close($code = null)
    {
        $this->getConnection()->close();
    }
}
