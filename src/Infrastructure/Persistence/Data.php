<?php

namespace App\Infrastructure\Persistence;

class Data implements DataInterface
{
    private $dataPath = null;

    public function __construct($dataPath)
    {
        $this->dataPath = $dataPath;
    }

    public function load()
    {
        if (file_exists($this->dataPath)) {
            return file_get_contents($this->dataPath);
        }

        return '';
    }

    public function save($data)
    {
        return file_put_contents($this->dataPath, $data);
    }
}
