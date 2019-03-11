<?php

namespace App\Core\Port;

interface PersistableInterface
{
    public function load();

    public function save();
}
