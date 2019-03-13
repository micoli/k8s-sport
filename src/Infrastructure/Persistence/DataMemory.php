<?php

namespace App\Infrastructure\Persistence;

use App\Core\Port\Persistence\PersistenceServiceInterface;

final class DataMemory implements PersistenceServiceInterface
{
    private static $data = null;

    public function get(): string
    {
        if (null !== self::$data) {
            return self::$data;
        }

        return '{}';
    }

    public function update(string $data)
    {
        self::$data = $data;

        return true;
    }
}
