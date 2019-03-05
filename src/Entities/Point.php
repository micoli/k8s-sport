<?php
/**
 * Created by PhpStorm.
 * User: micoli
 * Date: 28/02/2019
 * Time: 01:02
 */

namespace App\Entities;


class Point implements PointInterface
{

    private $x = 0;
    private $y = 0;


    public function __construct($x, $y)
    {
        $this->setCoord($x, $y);
    }

    public function setCoord($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getCoord(): array
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

    public function shiftXY($sx, $sy)
    {
        $this->shiftX($sx);
        $this->shiftY($sy);
    }

    public function fromRaw($struct)
    {
        if(isset($struct->x) && isset($struct->y)){
            $this->setCoord($struct->x, $struct->y);
        }
    }

    public function distanceTo(Point $to)
    {
        return (($to->getX() - $this->getX()) ** 2 + ($to->getY() - $this->getY()) ** 2) ** .5;
    }

    public function move($angle, $strength, $contraintWidth, $contraintHeight)
    {
        $this->shiftX($strength * cos($angle * M_PI / 180));
        $this->shiftY($strength * sin($angle * M_PI / 180));

        if ($this->getX() < 0 || $this->getX() > $contraintWidth) {
            $angle = 180 - $angle;
        } else if ($this->getY() < 0 || $this->getY() > $contraintHeight) {
            $angle = 360 - $angle;
        }
        return $angle;
    }

    public function moveTowards(PointInterface $finalPosition, $step)
    {

        $distance = $this->distanceTo($finalPosition);

        if($distance<=$step){
            $this->setCoord($finalPosition->getX(), $finalPosition->getY());
            return 0;
        }

        $ratio = $step / $distance;

        $this->setCoord(
            $this->getX() - $ratio * ($this->getX() - $finalPosition->getX()),
            $this->getY() - $ratio * ($this->getY() - $finalPosition->getY())
        );
        return $step;
    }

}
