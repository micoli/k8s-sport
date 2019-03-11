<?php

namespace App\Core\Port;

interface MovableInterface
{
    public function getUUID(): string;

    public function getPosition(): PointInterface;
}

/*
    public function load();
    public function unserialize($dto);
    public function serialize();
    private function save();
    public function setUUID($uuid);
    public function setPosition($x, $y);
    public function hitFrom(Point $fromPoint, $strength);
    public function hitTo(Point $toPoint, $strength);
    public function detectGoal();
    public function run();
*/
