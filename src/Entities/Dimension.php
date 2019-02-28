<?php
/**
 * Created by PhpStorm.
 * User: micoli
 * Date: 28/02/2019
 * Time: 01:02
 */

namespace App\Entities;


class Dimension implements DimensionInterface
{

    private $width = 0;
    private $height = 0;

    function __construct($width, $height)
    {
        $this->setValues($width, $height);
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
}