<?php

namespace App\Core\SharedKernel\Component;

interface PointInterface
{
    public function __construct($x, $y);

    public function setCoord($x, $y);

    public function getCoord(): array;

    public function getX();

    public function getY();

    public function setX($x);

    public function setY($y);

    public function shiftX($s);

    public function shiftY($s);

    public function shiftXY($sx, $sy);

    public function fromRaw($struct);

    public function distanceTo(PointInterface $to);

    public function move($angle, $strength, $contraintWidth, $contraintHeight);

    public function moveTowards(PointInterface $finalCoordinates, $step);

    public function getAngleBetween(PointInterface $fromPoint);
}
