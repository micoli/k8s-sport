<?php

namespace App\Entities;

interface PointInterface
{

    public function __construct($x, $y);
    public function setCoord($x, $y);
    public function getCoord():array;
    public function getX();
    public function getY();
    public function setX($x);
    public function setY($y);
    public function shiftX($s);
    public function shiftY($s);
    public function shiftXY($sx, $sy);
    public function fromRaw($struct);
    public function distanceTo(Point $to);
    public function move($angle, $strength, $contraintWidth, $contraintHeight);
    public function moveTowards(PointInterface $finalPosition, $step);
}
