<?php

namespace App\Entities;

use Ramsey\Uuid\Uuid;

class Ball implements MovableInterface
{
    private $uuid;

    /** @var Point */
    private $point;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->point = new Point(0,0);
    }

    public function getUUID(): string
    {
        return $this->uuid;
    }

    public function getPosition(): Point
    {
        return $this->point;
    }

    public function setPosition($x,$y)
    {
        return $this->point->setCoord($x,$y);
    }

}