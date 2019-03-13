<?php

namespace App\Presentation\Console;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Infrastructure\WebSocket\Server\Chat;
use App\Infrastructure\WebSocket\Server\MessageLogger;
use Ratchet\Server\IoServer;
use Monolog\Logger;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

final class wsserverCommand extends ContainerAwareCommand
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('application:run:ws-server')
            ->setDescription('run a websocket server');
    }

    private function runLogger()
    {
        $stdout = new \Monolog\Handler\StreamHandler('php://stdout');

        $logout = new Logger('SockOut');
        $login = new Logger('Sock-In');

        $login->pushHandler($stdout);
        $logout->pushHandler($stdout);

        $server = IoServer::factory(
            new MessageLogger(
                new HttpServer(
                    new WsServer(
                        new Chat()
                    )
                ), $login, $logout
            ), 82
        );
        $server->run();
    }

    private function runNoLogger()
    {
        $stdout = new \Monolog\Handler\StreamHandler('php://stdout');

        $logout = new Logger('SockOut');
        $login = new Logger('Sock-In');

        $login->pushHandler($stdout);
        $logout->pushHandler($stdout);

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Chat()
                )
            ), 82
        );
        $server->run();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->runNoLogger();
    }
}
