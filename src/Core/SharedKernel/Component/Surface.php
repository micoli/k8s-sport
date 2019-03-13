<?php

namespace App\Core\SharedKernel\Component;

final class Surface implements SurfaceInterface
{
    private $width = 0;
    private $height = 0;

    public function __construct($width, $height)
    {
        $this->setValues($width, $height);
    }

    public function getCenter(): PointInterface
    {
        return new Point($this->getWidth() / 2, $this->getHeight() / 2);
    }

    public function setValues($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function getCoord(): array
    {
        return [$this->width, $this->height];
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }
}
