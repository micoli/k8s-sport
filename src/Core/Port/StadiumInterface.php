<?php

namespace App\Core\Port;

interface StadiumInterface
{
    public function getDimension(): DimensionInterface;

    public function distributePlayer($teamName);
}
