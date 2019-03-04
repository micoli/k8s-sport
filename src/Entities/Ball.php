<?php

namespace App\Entities;

use App\Infrastructure\HttpClientInterface;
use App\Infrastructure\Data;
use Ramsey\Uuid\Uuid;

class Ball implements MovableInterface
{
    private $uuid;

    /** @var Point */
    private $position;

    private $speed = 0;

    private $angle = 0;

    /** @var Stadium */
    private $dimension;

    private $httpClient;

    /** @var Data */
    private $data;

    public function __construct(HttpClientInterface $httpClient, Data $data, Dimension $dimension)
    {
        $this->httpClient = $httpClient;
        $this->data = $data;
        $this->dimension = $dimension;
    }

    public function load()
    {
        $this->fromStruct($this->data->load());
    }

    public function fromStruct($dto)
    {
        $this->uuid = isset($dto->uuid) ? $dto->uuid : Uuid::uuid4();
        $this->speed = isset($dto->speed) ? $dto->speed : 0;
        $this->angle = isset($dto->angle) ? $dto->angle : 0;
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

    public function hitFrom(Point $fromPoint, $strength)
    {
        if ($this->position->distanceTo($fromPoint) < 2 && $strength > 0) {
            $attackAngle = rad2deg(atan2($fromPoint->getY() - $this->getPosition()->getY(), $fromPoint->getX() - $this->getPosition()->getX()));
            $this->angle = $this->position->move($attackAngle, $strength, $this->stadium->getDimension()->getWidth(), $this->stadium->getDimension()->getHeight());
            $this->speed = $strength - 0.5;
            $this->save();
        }
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
        $this->position = new point($x, $y);
        return $this;
    }

    public function getPosition(): Point
    {
        return $this->position;
    }

    public function run()
    {
        if ($this->speed > 0) {
            $this->angle = $this->position->move($this->angle, $this->speed, $this->stadium->getDimension()->getWidth(), $this->stadium->getDimension()->getHeight());
            $this->speed = $this->speed - 0.5;
        }
        $this->save();
    }
}
