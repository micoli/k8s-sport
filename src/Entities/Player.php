<?php

namespace App\Entities;

use Ramsey\Uuid\Uuid;

class Player implements MovableInterface,PlayerInterface
{
    private $uuid;

    /** @var Point */
    private $point;

    private $name;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->point = new Point(0,0);
        $this->name = 'toto';
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
        return $this->point;
    }

}