<?php

namespace App\Core\Component\Player\Domain;

use App\Core\SharedKernel\Component\Point;
use App\Core\SharedKernel\Component\PointInterface;

final class Player implements \Serializable
{
    private $uuid = null;

    private $skill = 1; //1...4

    private $speed = 3;

    private $random = 0.3;

    private $distanceToHit = 2;

    private $team = null;

    private $name = null;

    private $icon = null;

    private $position;

    /** @var PointInterface */
    private $coordinates;

    public function getUUID(): ?string
    {
        return $this->uuid;
    }

    public function setUUID($uuid)
    {
        return $this->uuid = $uuid;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    public function getTeam(): ?string
    {
        return $this->team;
    }

    public function setTeam($team)
    {
        $this->team = $team;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getDistanceToHit(): int
    {
        return $this->distanceToHit;
    }

    public function moveTowards(PointInterface $ballCoordinates)
    {
        $this->coordinates->moveTowards($ballCoordinates, $this->speed);

        $this->coordinates->shiftXY(random_int(-100 * $this->random, 100 * $this->random) / 100, random_int(-100 * $this->random, 100 * $this->random) / 100);
    }

    public function unserialize($dto)
    {
        $dto = json_decode($dto);
        if (isset($dto->type)) {
            $this->uuid = $dto->uuid;
            $this->name = $dto->name;
            $this->icon = $dto->icon;
            $this->team = $dto->team;
            $this->setSkill($dto->skill);
            $this->coordinates = new Point($dto->coordinates->x, $dto->coordinates->y);
        }

        return $this;
    }

    public function setSkill($skill)
    {
        $this->skill = $skill;
        $this->speed = 3 * $skill;
        $this->distanceToHit = 10 - 2 * $skill;
        $this->random = 0.3 / ($this->skill + 1);
    }

    public function serialize()
    {
        return json_encode([
            'type' => 'player',
            'uuid' => $this->uuid,
            'name' => $this->name,
            'icon' => $this->icon,
            'team' => $this->team,
            'skill' => $this->skill,
            'coordinates' => [
                'x' => $this->getCoordinates()->getX(),
                'y' => $this->getCoordinates()->getY(),
            ],
        ]);
    }

    public function getCoordinates(): PointInterface
    {
        return $this->coordinates;
    }

    public function setCoordinates(PointInterface $coordinates)
    {
        $this->coordinates = $coordinates;
    }
}
