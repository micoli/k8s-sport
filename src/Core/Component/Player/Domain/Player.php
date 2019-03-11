<?php

namespace App\Core\Component\Player\Domain;

use App\Core\Port\PointInterface;
use App\Core\SharedKernel\Component\Point;
use Ramsey\Uuid\Uuid;

class Player implements \Serializable
{
    private $uuid;

    private $speed = 3;
    private $random = 0.3;

    private $team;

    private $name;

    private $icon;

    /** @var PointInterface */
    private $position;

    public function getUUID(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setName($name)
    {
        $this->name=$name;
    }

    public function setIcon($icon)
    {
        $this->icon=$icon;
    }

    public function getTeam(): string
    {
        return $this->team;
    }

    public function getPosition(): PointInterface
    {
        return $this->position;
    }

    private function getDistancedToHit()
    {
        return 2;
    }

    public function moveTowards(PointInterface $ballPosition): bool
    {
        $this->position->moveTowards($ballPosition, $this->speed);

        $this->position->shiftXY(random_int(-100 * $this->random, 100 * $this->random) / 100, random_int(-100 * $this->random, 100 * $this->random) / 100);

        return $this->position->distanceTo($ballPosition) < $this->getDistancedToHit();
    }

    public function unserialize($dto)
    {
        $dto = json_decode($dto);
        $this->uuid = isset($dto->uuid) ? $dto->uuid : Uuid::uuid4();
        $this->name = isset($dto->name) ? $dto->name : 'name';
        $this->icon = isset($dto->icon) ? $dto->icon : 'icon';
        $this->team = isset($dto->team) ? $dto->team : getenv('APP_TEAM');
        $this->position = isset($dto->position) ? new Point($dto->position->x, $dto->position->y) : new Point(0, 0);

        return $this;
    }

    public function serialize()
    {
        return json_encode([
            'type' => 'player',
            'uuid' => $this->uuid,
            'name' => $this->name,
            'icon' => $this->icon,
            'team' => $this->team,
            'position' => [
                'x' => $this->getPosition()->getX(),
                'y' => $this->getPosition()->getY(),
            ],
        ]);
    }
}
