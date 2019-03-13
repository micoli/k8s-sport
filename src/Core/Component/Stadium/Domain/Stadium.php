<?php

namespace App\Core\Component\Stadium\Domain;

use App\Core\SharedKernel\Component\Point;
use App\Core\SharedKernel\Component\Surface;
use App\Core\SharedKernel\Component\SurfaceInterface;

final class Stadium implements \Serializable
{
    /** @var SurfaceInterface */
    private $surface;

    private $distributedPlayers = [];

    public function unserialize($dtoStr)
    {
        $dto = @json_decode($dtoStr);
        if (null === $dto) {
            throw new \ErrorException('Unserialize error in '.$dtoStr);
        }

        $this->surface = isset($dto->surface) ? new Surface($dto->surface->width, $dto->surface->height) : new Surface(80, 100);

        $this->distributedPlayers = isset($dto->distributedPlayers) ? $dto->distributedPlayers : [];
    }

    public function serialize()
    {
        return json_encode([
            'surface' => [
                'width' => $this->surface->getWidth(),
                'height' => $this->surface->getHeight(),
            ],
            'distributedPlayers' => $this->distributedPlayers,
        ]);
    }

    public function getSurface(): SurfaceInterface
    {
        return $this->surface;
    }

    public function getCenter(): Point
    {
        return $this->surface->getCenter();
    }

    public function getDistributedPlayersCount()
    {
        return count($this->distributedPlayers);
    }

    public function distributePlayer($teamName)
    {
        $players = $this->getPlayers();

        $team = array_keys($players[$teamName]);

        $team = array_filter($team, function ($v, $k) {
            return !in_array($v, $this->distributedPlayers);
        }, ARRAY_FILTER_USE_BOTH);

        if (0 == count($team)) {
            return [null, null];
        }

        $playerName = $team[array_rand($team)];

        $this->distributedPlayers[] = $playerName;

        return [$playerName, $players[$teamName][$playerName]];
    }

    private function getPlayers()
    {
        return [
            'blue' => [
                'Julian Dale' => 'gray/026-panda.svg',
                'Akshay Peterson' => 'gray/022-panther.svg',
                'Nadia Coates' => 'gray/002-wolf.svg',
                'Ellie-Mae Osborn' => 'gray/008-bull.svg',
                'Cayden Snow' => 'gray/009-bat.svg',
                'Anna-Marie Atherton' => 'gray/024-rhinoceros.svg',
                'John O\'Connel' => 'gray/012-lynx.svg',
                'Tyreece Greaves' => 'gray/003-raccoon.svg',
                'Ismail Douglas' => 'gray/035-rat.svg',
                'Kallum May' => 'gray/005-sheep.svg',
                'Susie Magana' => 'gray/007-koala.svg',
                'Alyssa Villa' => 'gray/004-badger.svg',
                'Cathy Tang' => 'gray/031-shark.svg',
                'Tamia Bassett' => 'gray/028-zebra.svg',
                'Youssef Sexton' => 'gray/014-donkey.svg',
            ],
            'red' => [
                'Orlaith Ewing' => 'brown/036-otter.svg',
                'Sachin Cameron' => 'brown/042-bison.svg',
                'Harleigh Albert' => 'brown/021-horse.svg',
                'Keith Kearney' => 'brown/049-boar.svg',
                'Alannah Ferrell' => 'brown/037-monkey.svg',
                'Asha Weber' => 'brown/040-owl.svg',
                'Ayyan Goodman' => 'brown/044-gorilla.svg',
                'Mohamad Bob' => 'brown/038-deer.svg',
                'Areebah Burrows' => 'brown/025-porcupine.svg',
                'Rebekka Hendrix' => 'brown/041-sea-lion.svg',
                'Kieron Burke' => 'brown/006-bear.svg',
                'Marianne Mill' => 'brown/047-walrus.svg',
                'Myron Logan' => 'brown/001-mole.svg',
                'Amber Kline' => 'brown/045-dog.svg',
                'Allan Kirkland' => 'brown/017-hippopotamus.svg',
            ],
            'purple' => [
                'Reuben Villanueva' => 'color/033-hen.svg',
                'Ross Huff' => 'color/043-kangaroo.svg',
                'Lia Stewart' => 'color/046-chipmunk.svg',
                'Greyson Bryan' => 'color/034-lion.svg',
                'Irene O\'Brien' => 'color/029-snake.svg',
                'Sharmin Choi' => 'color/020-frog.svg',
                'Melissa Joyner' => 'color/010-fox.svg',
                'Nabiha Bone' => 'color/032-chameleon.svg',
                'Kobe Costa' => 'color/027-tiger.svg',
                'Camille Harvey' => 'color/019-eagle.svg',
                'Lindsey Hope' => 'color/030-camel.svg',
                'Makayla Redfern' => 'color/050-turtle.svg',
                'Phillip Smyth' => 'color/048-giraffe.svg',
                'Tayyib Williams' => 'color/016-pig.svg',
                'Bruno Riddle' => 'color/015-cat.svg',
            ],
        ];
    }
}
