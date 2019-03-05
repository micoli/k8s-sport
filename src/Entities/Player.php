<?php

namespace App\Entities;

use App\Infrastructure\Data;
use Ramsey\Uuid\Uuid;
use App\Infrastructure\HttpClientInterface;


class Player implements MovableInterface, PlayerInterface
{
    private $uuid;

    private $speed = 4;
    private $random = 0.3;

    /** @var Point */
    private $position;

    private $name;

    private $httpClient;

    /** @var Data */
    private $data;

    public function __construct(HttpClientInterface $httpClient, Data $data)
    {
        $this->httpClient = $httpClient;
        $this->data = $data;
    }

    public function load()
    {
        $this->fromStruct($this->data->load());
    }

    public function fromStruct($dto)
    {
        $this->uuid = isset($dto->uuid) ? $dto->uuid : Uuid::uuid4();
        $this->name = isset($dto->name) ? $dto->name : '';
        $this->position = isset($dto->position) ? new Point($dto->position->x, $dto->position->y) : new Point(0, 0);
        return $this;
    }

    public function toStruct()
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'position' => [
                'x' => $this->getPosition()->getX(),
                'y' => $this->getPosition()->getY()
            ]
        ];
    }

    private function save()
    {
        $this->data->save($this->toStruct());
        $this->httpClient->send('PUT', 'http://stadium-php/stadium/player', $this->toStruct());
        return $this;
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

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
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

    public function run()
    {
        $ballPosition = new Point(0, 0);
        $ballPosition->fromRaw($this->httpClient->send('GET', 'http://ball-php/ball/position', null));
        $this->moveTowards($ballPosition);
    }

    private function sign($number)
    {
        return ($number > 0) ? 1 : (($number < 0) ? -1 : 0);
    }


    private function hitBall(PointInterface $ballPosition){
            
    }

    private function moveTowards(PointInterface $ballPosition)
    {

        $oldPosition = clone ($this->getPosition());
        $distanceDone = $this->position->moveTowards($ballPosition, $this->speed);
        //$this->position->shiftXY(random_int(-100*$this->random,100*$this->random)/100,random_int(-100*$this->random,100*$this->random)/100);
        $this->save();
        if($this->position->distanceTo($ballPosition)<2){
            $this->hitBall($ballPosition);
        }

        print (sprintf("(%0.2f,%0.2f) => (%0.2fx%0.2f) => (%0.2fx%0.2f)  @ %0.2f -\n",
            $oldPosition->getX(), $oldPosition->getY(),
            $this->position->getX(), $this->position->getY(),
            $ballPosition->getX(), $ballPosition->getY(),
            $this->speed
        ));
    }

}
