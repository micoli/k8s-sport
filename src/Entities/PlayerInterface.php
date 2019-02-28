<?php

namespace App\Entities;

interface PlayerInterface
{
    public function getName(): string;

    public function getUUID(): string;
}