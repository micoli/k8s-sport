<?php

namespace App\Infrastructure;


class Data
{
    private $dataPath = null;

    public function __construct($dataPath)
    {
        $this->dataPath = $dataPath;
    }

    public function load()
    {
        if (file_exists($this->dataPath)) {
            return json_decode(file_get_contents($this->dataPath));
        }
        return new \StdClass();
    }

    public function save($data)
    {
        return file_put_contents($this->dataPath, json_encode($data));
    }
}
