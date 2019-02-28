<?php
/**
 * Created by PhpStorm.
 * User: micoli
 * Date: 28/02/2019
 * Time: 01:02
 */

namespace App\Entities;


class Point
{

    private $x = 0;
    private $y = 0;


    public function __construct($x, $y){
        $this->setCoord($x, $y);
    }

    public function setCoord($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getCoord()
    {
        return [$this->x, $this->y];
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function setX($x)
    {
        $this->x = $x;
    }

    public function setY($y)
    {
        $this->y = $y;
    }

    public function shiftX($s)
    {
        $this->x += $s;
    }

    public function shiftY($s)
    {
        $this->y += $s;
    }


    public function fromRaw($struct)
    {
        $this->setCoord($struct->x,$struct->y);
    }
}
