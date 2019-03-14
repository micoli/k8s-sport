<?php

namespace App\Core\SharedKernel\Component;

final class Point implements PointInterface
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
        if (isset($struct->x) && isset($struct->y)) {
            $this->setCoord($struct->x, $struct->y);
        }
    }

    public function distanceTo(PointInterface $to)
    {
        return (($to->getX() - $this->getX()) ** 2 + ($to->getY() - $this->getY()) ** 2) ** .5;
    }

    public function move($angle, $strength, $contraintWidth, $contraintHeight)
    {
        $nb = 0;
        do {
            //print sprintf("%sx%s\n", $this->getY(), $this->getX());
            $this->shiftX($strength * cos(deg2rad($angle)));
            $this->shiftY($strength * sin(deg2rad($angle)));

            if ($this->getX() < 0 || $this->getX() > $contraintWidth) {
                $angle = 180 - $angle;
            } elseif ($this->getY() < 0 || $this->getY() > $contraintHeight) {
                $angle = 360 - $angle;
            }
            if ($nb++ > 10) {
                break;
            }
        } while (!($this->getX() > 0 && $this->getX() < $contraintWidth && $this->getY() > 0 && $this->getY() < $contraintHeight));

        return $angle;
    }

    public function moveTowards(PointInterface $finalCoordinates, $step)
    {
        $distance = $this->distanceTo($finalCoordinates);

        if ($distance <= $step) {
            $this->setCoord($finalCoordinates->getX(), $finalCoordinates->getY());

            return 0;
        }

        $ratio = $step / $distance;

        $this->setCoord(
            $this->getX() - $ratio * ($this->getX() - $finalCoordinates->getX()),
            $this->getY() - $ratio * ($this->getY() - $finalCoordinates->getY())
        );

        return $step;
    }

    public function getAngleBetween(PointInterface $fromPoint)
    {
        return rad2deg(atan2($this->getY() - $fromPoint->getY(), $this->getX() - $fromPoint->getX()));
    }
}
