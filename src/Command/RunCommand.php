<?php

namespace App\Command;

use App\Entities\Ball;
use App\Entities\Player;
use App\Entities\StadiumInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use App\Infrastructure\Chat;
use App\Infrastructure\MessageLogger;
use Ratchet\Server\IoServer;
use Monolog\Logger;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class RunCommand extends ContainerAwareCommand
{
    /** @var StadiumInterface */
    private $stadium;

    /** @var PlayerInterface */
    private $player;

    /** @var BallInterface */
    private $ball;

    public function __construct(StadiumInterface $stadium, Ball $ball, Player $player)
    {
        $this->stadium = $stadium;
        $this->ball = $ball;
        $this->player = $player;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('application:run')
            ->setDescription('run an entity forever');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        for ($i = 0; $i < 8; $i++) {
            $type = getEnv('APP_TYPE');
            switch ($type) {
                case 'ball':
                    $this->ball->load();
                    $this->ball->run();
                    break;
                case 'player':
            print $type;
                    $this->player->load();
                    print "ee";
                    $this->player->run();
                    break;
                case 'ws-server':
                    $stdout = new \Monolog\Handler\StreamHandler('php://stdout');

                    $logout = new Logger('SockOut');
                    $login  = new Logger('Sock-In');

                    $login->pushHandler($stdout);
                    $logout->pushHandler($stdout);

                    $server = IoServer::factory(
                        new MessageLogger(
                            new HttpServer(
                                new WsServer(
                                    new Chat()
                                )
                            )
                            , $login
                            , $logout
                        )
                        , 82
                    );
                    $server->run();
                    break;
            }
            sleep(1);
        }
    }
}

