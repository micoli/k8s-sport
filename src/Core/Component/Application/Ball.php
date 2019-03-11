<?php

namespace App\Core\Component\Application;

use App\Core\Component\Domain\Point;
use App\Core\Port\BallInterface;
use App\Core\Port\DimensionInterface;
use App\Core\Port\MovableInterface;
use App\Core\Port\PersistableInterface;
use App\Core\Port\PointInterface;
use App\Core\Port\RunableInterface;
use App\Infrastructure\Communication\Http\HttpClientInterface;
use App\Infrastructure\Persistence\DataInterface;
use App\Infrastructure\WebSocket\Client\WsClientInterface;
use Ramsey\Uuid\Uuid;
use Psr\Log\LoggerInterface;

final class Ball implements MovableInterface, PersistableInterface, RunableInterface, BallInterface, \Serializable
{
    private $uuid;

    /** @var Point */
    private $position;

    /** @var Stadium */
    private $dimension;

    /** @var LoggerInterface */
    private $logger;

    /** @var DataInterface */
    private $data;

    private $httpClient;

    private $speed = 0;

    private $angle = 0;

    /** @var WsClientInterface */
    private $WsClient;

    public function __construct(HttpClientInterface $httpClient, DataInterface $data, DimensionInterface $dimension, LoggerInterface $logger, WsClientInterface $WsClient)
    {
        $this->httpClient = $httpClient;
        $this->data = $data;
        $this->dimension = $dimension;
        $this->logger = $logger;
        $this->WsClient = $WsClient;
    }

    public function load()
    {
        $this->unserialize($this->data->load());
        //$this->logger->info(sprintf('Ball load %s, %sx%s', $this->getUUID(), $this->position->getX(), $this->position->getY()));
    }

    public function unserialize($dto)
    {
        $dto = json_decode($dto);
        $this->uuid = isset($dto->uuid) ? $dto->uuid : Uuid::uuid4();
        $this->speed = isset($dto->speed) ? $dto->speed : 0;
        $this->angle = isset($dto->angle) ? $dto->angle : 25;
        $this->position = isset($dto->position) ? new Point($dto->position->x, $dto->position->y) : $this->dimension->getCenter();

        return $this;
    }

    public function serialize()
    {
        return json_encode([
            'type' => 'ball',
            'uuid' => $this->uuid,
            'speed' => $this->speed,
            'angle' => $this->angle,
            'position' => [
                'x' => $this->getPosition()->getX(),
                'y' => $this->getPosition()->getY(),
            ],
        ]);
    }

    public function save()
    {
        $serialized = $this->serialize();
        $this->data->save($serialized);
        //$this->httpClient->send('PUT', 'http://stadium-php/stadium/ball', $this->serialize());

        $this->WsClient->send('broadcast:'.$serialized);
        $this->WsClient->close();
    }

    public function getUUID(): string
    {
        return $this->uuid;
    }

    public function getPosition(): PointInterface
    {
        return $this->position;
    }

    public function hitFrom(PointInterface $fromPoint, $strength)
    {
        $this->logger->info(sprintf('hit from %s / %s', json_encode($fromPoint), $this->position->distanceTo($fromPoint)));
        if ($this->position->distanceTo($fromPoint) < 2 && $strength > 0) {
            $attackAngle = $this->getPosition()->getAngleBetween($fromPoint);
            $this->angle = $this->position->move($attackAngle, $strength, 80, 100);
            $this->speed = $strength;
            $this->detectGoal();
            $this->save();
        }
    }

    public function hitTo(PointInterface $toPoint, $strength)
    {
        $this->logger->info(sprintf('hit to %s ', json_encode($toPoint)));
        $attackAngle = $toPoint->getAngleBetween($this->getPosition());
        $this->angle = $this->position->move($attackAngle, $strength, 80, 100);
        $this->speed = $strength;
        $this->detectGoal();
        $this->save();
    }

    public function detectGoal()
    {
        if ($this->getPosition()->getX() > (40 - 10) && $this->getPosition()->getX() < (40 + 10)) {
            if ($this->getPosition()->getY() < 10 || $this->getPosition()->getY() > 90) {
                $this->position = $this->dimension->getCenter();
                $this->speed = 0;

                return true;
            }
        }

        return false;
    }

    public function run()
    {
        $this->logger->info(sprintf('ball run @ speed %s', $this->speed));
        if ($this->speed > 0) {
            $this->angle = $this->position->move($this->angle, $this->speed, 80, 100);
            $this->speed = max($this->speed - 0.5, 0);
        }
        $this->save();
    }
}
