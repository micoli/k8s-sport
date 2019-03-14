<?php

namespace App\Core\Component\Ball\Domain;

use App\Core\SharedKernel\Component\Point;
use App\Core\SharedKernel\Component\PointInterface;
use App\Core\SharedKernel\Component\Surface;
use App\Core\SharedKernel\Component\SurfaceInterface;

final class Ball implements \Serializable
{
    private $uuid = '-';

    /** @var PointInterface */
    private $coordinates;

    /** @var SurfaceInterface */
    private $constraint;

    private $speed = 0;

    private $angle = 0;

    private $goalRangeWidth = 0;

    public function getUUID(): string
    {
        return $this->uuid;
    }

    public function setUUID($uuid)
    {
        return $this->uuid = $uuid;
    }

    public function setCooordinates(PointInterface $coordinates)
    {
        return $this->coordinates = $coordinates;
    }

    public function hitFrom(PointInterface $fromPoint, $strength)
    {
        if ($this->coordinates->distanceTo($fromPoint) < 2 && $strength > 0) {
            $attackAngle = $this->getCoordinates()->getAngleBetween($fromPoint);
            $this->angle = $this->coordinates->move($attackAngle, $strength, 80, 100);
            $this->speed = $strength;
            $this->detectGoal();
        }
    }

    public function getCoordinates(): PointInterface
    {
        return $this->coordinates;
    }

    public function setCoordinates(PointInterface $coordinates)
    {
        $this->coordinates = $coordinates;
    }

    private function detectGoal()
    {
        if ($this->getCoordinates()->getX() > (40 - 10) && $this->getCoordinates()->getX() < (40 + 10)) {
            if ($this->getCoordinates()->getY() < 10 || $this->getCoordinates()->getY() > 90) {
                $this->coordinates = $this->getConstraint()->getCenter();
                $this->speed = 0;

                return true;
            }
        }

        return false;
    }

    public function getConstraint(): SurfaceInterface
    {
        return $this->constraint;
    }

    public function setConstraint(SurfaceInterface $constraint)
    {
        return $this->constraint = $constraint;
    }

    public function hitTo(PointInterface $toPoint, $strength)
    {
        $attackAngle = $toPoint->getAngleBetween($this->getCoordinates());
        $this->angle = $this->coordinates->move($attackAngle, $strength, 80, 100);
        $this->speed = $strength;
        $this->detectGoal();
    }

    public function unserialize($dto)
    {
        $dto = json_decode($dto);
        if (isset($dto->type)) {
            $this->uuid = $dto->uuid;
            $this->speed = $dto->speed;
            $this->angle = $dto->angle;
            $this->coordinates = new Point($dto->coordinates->x, $dto->coordinates->y);
            $this->constraint = new Surface($dto->constraint->width, $dto->constraint->height);
            $this->goalRangeWidth = $dto->goalRangeWidth;
        }

        return $this;
    }

    public function serialize()
    {
        return json_encode([
            'type' => 'ball',
            'uuid' => $this->uuid,
            'speed' => $this->speed,
            'angle' => $this->angle,
            'coordinates' => [
                'x' => $this->getCoordinates()->getX(),
                'y' => $this->getCoordinates()->getY(),
            ],
            'constraint' => [
                'width' => $this->getConstraint()->getWidth(),
                'height' => $this->getConstraint()->getHeight(),
            ],
            'goalRangeWidth' => $this->goalRangeWidth,
        ]);
    }

    public function move()
    {
        $outAngle = $this->getCoordinates()->move($this->getAngle(), $this->getSpeed(), $this->getConstraint()->getWidth(), $this->getConstraint()->getHeight());
        $this->setAngle($outAngle);
    }

    public function getAngle()
    {
        return $this->angle;
    }

    public function setAngle($angle)
    {
        $this->angle = $angle;
    }

    public function getSpeed()
    {
        return $this->speed;
    }

    public function setSpeed($speed)
    {
        $this->speed = $speed;
    }
}
