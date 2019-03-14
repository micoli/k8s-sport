<?php

namespace App\Core\Component\Player\Application\Service;

use App\Core\Component\Player\Domain\Player;
use App\Core\Port\Notification\NotificationEmitterInterface;
use App\Core\Port\ServiceAccess\ServiceAccessInterface;
use App\Core\SharedKernel\Component\Point;
use App\Core\SharedKernel\Component\PointInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

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

    public function create(Player $player)
    {
        $player->setUUID(Uuid::uuid4());

        $player->setPosition(getenv('APP_PLAYER_POSITION'));

        $player->setSkill(getenv('APP_PLAYER_SKILL'));

        $player->setTeam(getenv('APP_PLAYER_TEAM'));

        $player->setCoordinates(new Point(0, 0));

        $nb = 0;
        while (null === $player->getName() && $nb++ < 10) {
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
        $coordinatesStruct = $this->serviceAccess->get('ball-php', 'ball/coordinates');
        if (null !== $coordinatesStruct) {
            $ballCoordinates = new Point(0, 0);
            $ballCoordinates->fromRaw($coordinatesStruct);

            $player->moveTowards($ballCoordinates);

            if ($player->getCoordinates()->distanceTo($ballCoordinates) < $player->getDistanceToHit()) {
                $this->hitBall($player, $ballCoordinates);
            }

            $this->logger->info(sprintf('player run %sx%s, ball :%sx%s',
                $player->getCoordinates()->getX(),
                $player->getCoordinates()->getY(),
                $ballCoordinates->getX(),
                $ballCoordinates->getY()
            ));
            $this->notificationEmitter->broadcast($player->serialize());
        }
    }

    public function hitBall(Player $player, PointInterface $ballCoordinates)
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

    public function hitBallFrom(Player $player, PointInterface $ballCoordinates)
    {
        $this->logger->info(sprintf('player hit ball'));
        $this->serviceAccess->put('ball-php',
            sprintf('ball/hit/%s/%s/%s/%s/%s',
                $player->getCoordinates()->getX(),
                $player->getCoordinates()->getY(),
                5,
                $player->getUUID(),
                $player->getName()
            ),
            null
        );
    }
}
