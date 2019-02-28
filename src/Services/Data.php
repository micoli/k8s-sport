<?php

namespace App\Services;


class Data
{
    private $dataPath = null;

    public function __construct($dataPath)
    {
        $this->dataPath = $dataPath;
    }

    public function get()
    {
        if (file_exists($this->dataPath)) {
            return json_decode(file_get_contents($this->dataPath));
        }
        return new \StdClass();
    }

    public function set($data)
    {
        return file_get_contents($this->dataPath, json_encode($data));
    }
}
