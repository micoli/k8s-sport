<?php

namespace App\Infrastructure\Persistence;

class DataMemory implements DataInterface
{
    private static $data = null;

    public function load()
    {
        if (null !== self::$data) {
            return self::$data;
        }

        return '';
    }

    public function save($data)
    {
        self::$data = $data;

        return true;
    }
}
