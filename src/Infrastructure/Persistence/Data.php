<?php

namespace App\Infrastructure\Persistence;

use App\Core\Port\Persistence\PersistenceServiceInterface;

final class Data implements PersistenceServiceInterface
{
    private $dataPath = null;

    public function __construct($dataPath)
    {
        $this->dataPath = $dataPath;
    }

    public function get(): string
    {
        if (file_exists($this->dataPath)) {
            return (string) file_get_contents($this->dataPath);
        }

        return '{}';
    }

    public function update(string $data)
    {
        return file_put_contents($this->dataPath, $data);
    }
}
