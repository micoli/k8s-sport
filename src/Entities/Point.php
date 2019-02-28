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

    public function setCoord($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getCoord()
    {
        return [$this->x, $this->y];
    }
}