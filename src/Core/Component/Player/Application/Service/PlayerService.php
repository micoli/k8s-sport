<?php

namespace App\Core\Component\Player\Application\Service;

use App\Core\Component\Player\Domain\Player;
use App\Core\Port\Notification\NotificationEmitterInterface;
use App\Core\Port\ServiceAccess\ServiceAccessInterface;
use App\Core\SharedKernel\Component\Point;
use App\Core\SharedKernel\Component\PointInterface;
use Psr\Log\LoggerInterface;

final class PlayerService
{
    /** @var ServiceAccessInterface */
    private $serviceAccess;

    /** @var NotificationEmitterInterface */
    private $notificationEmitter;

    public function __construct(LoggerInterface $logger, NotificationEmitterInterface $notificationEmitter, ServiceAccessInterface $serviceAccess)
    {
        $this->logger = $logger;
        $this->notificationEmitter = $notificationEmitter;
        $this->serviceAccess = $serviceAccess;
    }

    public function init(Player $player)
    {
        if ('icon' == $player->getIcon()) {
            $struct = $this->serviceAccess->send('GET', 'http://stadium-php/stadium/distributePlayer/'.$player->getTeam(), null);
            $player->setName($struct->name);
            $player->setIcon($struct->icon);
        }
    }

    public function run(Player $player)
    {
        $ballPosition = new Point(0, 0);
        $positionStruct = $this->serviceAccess->send('GET', 'http://ball-php/ball/position', null);
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
            $this->notificationEmitter->broadcast($player->serialize());
        }
    }

    public function hitBall(Player $player, PointInterface $ballPosition)
    {
        $this->logger->info(sprintf('player hit ball'));
        $this->serviceAccess->send('PUT', sprintf('http://ball-php/ball/hitto/%s/%s/%s/%s/%s',
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
        $this->serviceAccess->send('PUT', sprintf('http://ball-php/ball/hit/%s/%s/%s/%s/%s',
            $player->getPosition()->getX(),
            $player->getPosition()->getY(),
            5,
            $player->getUUID(),
            $player->getName()
        ), null);
    }
}
