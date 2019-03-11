<?php

namespace App\Infrastructure\Persistence;

interface DataInterface
{
    public function load();

    public function save($data);
}
