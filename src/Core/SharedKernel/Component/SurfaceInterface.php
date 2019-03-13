<?php

namespace App\Core\SharedKernel\Component;

interface SurfaceInterface
{
    public function setValues($width, $height);

    public function getCoord(): array;

    public function getWidth();

    public function getHeight();

    public function getCenter(): PointInterface;
}
