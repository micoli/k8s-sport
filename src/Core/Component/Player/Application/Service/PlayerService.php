<?php

namespace App\Core\Component\Player\Application\Service;

use App\Core\Component\Player\Domain\Player;
use App\Core\Port\PointInterface;
use App\Core\SharedKernel\Component\Point;
use App\Infrastructure\Communication\Http\HttpClientInterface;
use App\Infrastructure\WebSocket\Client\WsClientInterface;
use Psr\Log\LoggerInterface;

class PlayerService
{
    /** @var HttpClientInterface */
    private $httpClient;

    public function __construct(LoggerInterface $logger, WsClientInterface $WsClient, HttpClientInterface $httpClient)
    {
        $this->logger = $logger;
        $this->WsClient = $WsClient;
        $this->httpClient = $httpClient;
    }

    public function init(Player $player){
        if ($player->getIcon()=='icon'){
            $struct = $this->httpClient->send('GET', 'http://stadium-php/stadium/distributePlayer/'.getenv('APP_TEAM'), null);
            $player->setName($struct->name);
            $player->setIcon($struct->icon);
        }
    }

    public function run(Player $player)
    {
        //$this->WsClient->send('broadcast:'.$ball->serialize());
        $ballPosition = new Point(0, 0);
        $positionStruct = $this->httpClient->send('GET', 'http://ball-php/ball/position', null);
        if (null !== $positionStruct) {
            $ballPosition->fromRaw($positionStruct);
            if ($player->moveTowards($ballPosition)) {
                $this->hitBall($player, $ballPosition);
            }

            $this->logger->info(sprintf('player run %sx%s, ball :%sx%s',
                $player->getPosition()->getX(),
                $player->getPosition()->getY(),
                $ballPosition->getX(),
                $ballPosition->getY()
            ));
            $this->WsClient->send('broadcast:'.$player->serialize());
            $this->WsClient->close();
        }
    }

    public function hitBall(Player $player, PointInterface $ballPosition)
    {
        $this->logger->info(sprintf('player hit ball'));
        $this->httpClient->send('PUT', sprintf('http://ball-php/ball/hitto/%s/%s/%s/%s/%s',
            40,
            'blue' == $player->getTeam() ? 5 : (100 - 5),
            5,
            $player->getUUID(),
            $player->getName()
        ), null);
    }

    public function hitBallFrom(Player $player, PointInterface $ballPosition)
    {
        $this->logger->info(sprintf('player hit ball'));
        $this->httpClient->send('PUT', sprintf('http://ball-php/ball/hit/%s/%s/%s/%s/%s',
            $player->getPosition()->getX(),
            $player->getPosition()->getY(),
            5,
            $player->getUUID(),
            $player->getName()
        ), null);
    }
}
