<?php

namespace App\Entities;

class Stadium implements StadiumInterface
{
    /** @var DimensionInterface */
    private $dimension;

    public function __construct(DimensionInterface $dimension)
    {
        $this->dimension = $dimension;
    }

    public function getDimension(): DimensionInterface
    {
        return $this->dimension;
    }

}