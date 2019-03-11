<?php

namespace App\Core\Port;

interface DimensionInterface
{
    public function setValues($width, $height);

    public function getCoord(): array;

    public function getWidth();

    public function getHeight();

    public function getCenter(): PointInterface;
}
