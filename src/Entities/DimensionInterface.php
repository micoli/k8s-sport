<?php
/**
 * Created by PhpStorm.
 * User: micoli
 * Date: 28/02/2019
 * Time: 01:02
 */

namespace App\Entities;


interface DimensionInterface
{

    public function setValues($width, $height);
    public function getCoord():array;
}