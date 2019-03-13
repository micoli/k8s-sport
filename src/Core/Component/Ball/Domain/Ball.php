<?php

namespace App\Core\Component\Ball\Domain;

use App\Core\SharedKernel\Component\Point;
use App\Core\SharedKernel\Component\PointInterface;
use Ramsey\Uuid\Uuid;

final class Ball implements \Serializable
{
    private $uuid;

    /** @var PointInterface */
    private $position;

    private $speed = 0;

    private $angle = 0;

    public function getUUID(): string
    {
        return $this->uuid;
    }

    public function getPosition(): PointInterface
    {
        return $this->position;
    }

    public function getSpeed()
    {
        return $this->speed;
    }

    public function setSpeed($speed)
    {
        $this->speed = $speed;
    }

    public function getAngle()
    {
        return $this->angle;
    }

    public function setAngle($angle)
    {
        $this->angle = $angle;
    }

    public function hitFrom(PointInterface $fromPoint, $strength)
    {
        if ($this->position->distanceTo($fromPoint) < 2 && $strength > 0) {
            $attackAngle = $this->getPosition()->getAngleBetween($fromPoint);
            $this->angle = $this->position->move($attackAngle, $strength, 80, 100);
            $this->speed = $strength;
            $this->detectGoal();
        }
    }

    public function hitTo(PointInterface $toPoint, $strength)
    {
        $attackAngle = $toPoint->getAngleBetween($this->getPosition());
        $this->angle = $this->position->move($attackAngle, $strength, 80, 100);
        $this->speed = $strength;
        $this->detectGoal();
    }

    private function detectGoal()
    {
        if ($this->getPosition()->getX() > (40 - 10) && $this->getPosition()->getX() < (40 + 10)) {
            if ($this->getPosition()->getY() < 10 || $this->getPosition()->getY() > 90) {
                $this->position = new Point(0, 0);
                $this->speed = 0;

                return true;
            }
        }

        return false;
    }

    public function unserialize($dto)
    {
        $dto = json_decode($dto);
        $this->uuid = isset($dto->uuid) ? $dto->uuid : Uuid::uuid4();
        $this->speed = isset($dto->speed) ? $dto->speed : 0;
        $this->angle = isset($dto->angle) ? $dto->angle : 25;
        $this->position = isset($dto->position) ? new Point($dto->position->x, $dto->position->y) : new Point(0, 0);

        return $this;
    }

    public function serialize()
    {
        return json_encode([
            'type' => 'ball',
            'uuid' => $this->uuid,
            'speed' => $this->speed,
            'angle' => $this->angle,
            'position' => [
                'x' => $this->getPosition()->getX(),
                'y' => $this->getPosition()->getY(),
            ],
        ]);
    }
}
