<?php

namespace App\Core\Port;

interface PlayerInterface
{
    public function getName(): string;

    public function getTeam(): string;
}
