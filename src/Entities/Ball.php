<?php

namespace App\Entities;

use App\Infrastructure\HttpClientInterface;
use App\Services\Data;
use Ramsey\Uuid\Uuid;

class Ball implements MovableInterface
{
    private $uuid;

    /** @var Point */
    private $position;

    /** @var Stadium */
    private $stadium;

    private $httpClient;

    /** @var Data */
    private $data;

    public function __construct(HttpClientInterface $httpClient, Data $data,StadiumInterface $stadium)
    {
        $this->httpClient = $httpClient;
        $this->data = $data;
        $this->stadium = $stadium;
        $this->load();
    }

    private function load()
    {
        $dto = $this->data->get();
        $this->uuid = isset($dto->uuid) ? $dto->uuid : Uuid::uuid4();
        $this->position = isset($dto->position) ? new Point($dto->position->x, $dto->position->x) : $this->stadium->getCenter();
    }

    private function save()
    {
        $this->data->save([
            'uuid' => $this->uuid,
            'point' => [
                'x'=> $this->getPosition()->getX(),
                'y'=> $this->getPosition()->getY()
            ]
        ]);
    }

    public function getUUID(): string
    {
        return $this->uuid;
    }

    public function getPosition(): Point
    {
        return $this->position;
    }

    public function setPosition($x, $y)
    {
        return $this->position->setCoord($x, $y);
    }

    public function run(){

    }
}
