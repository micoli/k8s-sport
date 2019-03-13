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

    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger, NotificationEmitterInterface $notificationEmitter, ServiceAccessInterface $serviceAccess)
    {
        $this->logger = $logger;
        $this->notificationEmitter = $notificationEmitter;
        $this->serviceAccess = $serviceAccess;
    }

    public function init(Player $player)
    {
        if (null === $player->getIcon()) {
            $struct = $this->serviceAccess->get('stadium-php', 'stadium/distributePlayer/'.$player->getTeam());
            if (isset($struct->name)) {
                $player->setName($struct->name);
            }
            if (isset($struct->icon)) {
                $player->setIcon($struct->icon);
            }
        }
    }

    public function run(Player $player)
    {
        $ballPosition = new Point(0, 0);
        $positionStruct = $this->serviceAccess->get('ball-php', 'ball/position');
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
        $this->serviceAccess->put('ball-php',
            sprintf('ball/hitto/%s/%s/%s/%s/%s',
                40,
                'blue' == $player->getTeam() ? 5 : (100 - 5),
                5,
                $player->getUUID(),
                $player->getName()
            ),
            null
        );
    }

    public function hitBallFrom(Player $player, PointInterface $ballPosition)
    {
        $this->logger->info(sprintf('player hit ball'));
        $this->serviceAccess->put('ball-php',
            sprintf('ball/hit/%s/%s/%s/%s/%s',
                $player->getPosition()->getX(),
                $player->getPosition()->getY(),
                5,
                $player->getUUID(),
                $player->getName()
            ),
            null
        );
    }
}
