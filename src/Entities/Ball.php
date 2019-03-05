<?php

namespace App\Entities;

use App\Infrastructure\HttpClientInterface;
use App\Infrastructure\Data;
use Ramsey\Uuid\Uuid;
use Psr\Log\LoggerInterface;

class Ball implements MovableInterface
{
    private $uuid;

    /** @var Point */
    private $position;

    /** @var Stadium */
    private $dimension;

    /** @var LoggerInterface */
    private $logger;

    /** @var Data */
    private $data;

    private $httpClient;

    private $speed = 0;

    private $angle = 0;

    public function __construct(HttpClientInterface $httpClient, Data $data, Dimension $dimension, LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->data = $data;
        $this->dimension = $dimension;
        $this->logger = $logger;
    }

    public function load()
    {
        $this->fromStruct($this->data->load());
        $this->logger->info(sprintf('Ball load %s, %sx%s', $this->getUUID(), $this->position->getX(), $this->position->getY()));
    }

    public function fromStruct($dto)
    {
        $this->uuid = isset($dto->uuid) ? $dto->uuid : Uuid::uuid4();
        $this->speed = isset($dto->speed) ? $dto->speed : 5;
        $this->angle = isset($dto->angle) ? $dto->angle : 25;
        $this->position = isset($dto->position) ? new Point($dto->position->x, $dto->position->y) : $this->dimension->getCenter();
    }

    public function toStruct()
    {
        return [
            'uuid' => $this->uuid,
            'speed' => $this->speed,
            'angle' => $this->angle,
            'position' => [
                'x' => $this->getPosition()->getX(),
                'y' => $this->getPosition()->getY()
            ]
        ];
    }

    private function save()
    {
        $this->data->save($this->toStruct());
        $this->httpClient->send('PUT', 'http://stadium-php/stadium/ball', $this->toStruct());
    }

    public function setUUID($uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getUUID(): string
    {
        return $this->uuid;
    }

    public function setPosition($x, $y)
    {
        $this->position = new Point($x, $y);
        return $this;
    }

    public function getPosition(): Point
    {
        return $this->position;
    }

    public function hitFrom(Point $fromPoint, $strength)
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

    public function hitTo(Point $toPoint, $strength)
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
            }
        }
    }

    public function run()
    {
        $this->logger->debug(sprintf('ball run'));
        if ($this->speed > 0) {
            $this->angle = $this->position->move($this->angle, $this->speed, 80, 100);
            $this->speed = max($this->speed - 0.5, 0);
        }
        $this->save();
    }
}
