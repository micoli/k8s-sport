<?php

namespace App\Entities;

use App\Services\Data;
use Ramsey\Uuid\Uuid;
use App\Infrastructure\HttpClientInterface;


class Player implements MovableInterface, PlayerInterface
{
    private $uuid;

    private $speed=2;

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
        $this->load();
    }

    private function load()
    {
        $dto = $this->data->get();
        $this->uuid = isset($dto->uuid) ? $dto->uuid : Uuid::uuid4();
        $this->position = isset($dto->position) ? new Point($dto->position->x, $dto->position->x) : new Point(0, 0);
    }

    private function save()
    {
        $this->data->save([
            'uuid' => $this->uuid,
            'point' => [
                'x' => $this->getPosition()->getX(),
                'y' => $this->getPosition()->getY()
            ]
        ]);
    }

    public function getUUID(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPosition(): Point
    {
        return $this->position;
    }

    public function run()
    {

        $ballPosition = new Point(0,0);
        $ballPosition->fromRaw($this->httpClient->send('GET', 'http://ball-php/ball/position', []));
        $this->moveTowards($ballPosition);
    }

    private function sign( $number ) {
        return ( $number > 0 ) ? 1 : ( ( $number < 0 ) ? -1 : 0 );
    }

    private function moveTowards($ballPosition)
    {
        $oldPosition = clone($this->position);
        $deltaX = $this->position->getX()-$ballPosition->getX();
        if(abs($deltaX)>$this->speed){
            $this->position->shiftX($this->sign($deltaX)*$this->speed);
        }else{
            $this->position->setX($ballPosition->getX());
        }

        $deltaY = $this->position->getY()-$ballPosition->getY();
        if(abs($deltaY)>$this->speed){
            $this->position->shiftY($this->sign($deltaY)*$this->speed);
        }else{
            $this->position->setY($ballPosition->getY());
        }

        print (sprintf ("%sx%s => %sx%s => %sx%s ",
            $oldPosition->getX(),$oldPosition->getY(),
            $ballPosition->getX(),$ballPosition->getY(),
            $this->position->getX(),$this->position->getY()
        ));
    }

}
