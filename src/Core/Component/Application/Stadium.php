<?php

namespace App\Core\Component\Application;

use App\Core\Component\Domain\Dimension;
use App\Core\Port\DimensionInterface;
use App\Core\Port\PersistableInterface;
use App\Core\Port\StadiumInterface;
use App\Infrastructure\Persistence\DataInterface;

final class Stadium implements StadiumInterface, PersistableInterface
{
    /** @var DimensionInterface */
    private $dimension;

    /** @var DataInterface */
    private $data;

    private $distributedPlayers = [];

    public function __construct(DimensionInterface $dimension, DataInterface $data)
    {
        $this->data = $data;
        $this->dimension = $dimension;
    }

    public function load()
    {
        $this->unserialize($this->data->load());
    }

    public function unserialize($dto)
    {
        $dto = json_decode($dto);

        $this->dimension = isset($dto->dimension) ? new Dimension($dto->dimension->width, $dto->dimension->height) : new Dimension(80, 100);

        $this->distributedPlayers = isset($dto->distributedPlayers) ? $this->distributedPlayers = $dto->distributedPlayers : [];

        return $this;
    }

    public function save()
    {
        $this->data->save(json_encode([
            'dimension' => [
                'width' => $this->dimension->getWidth(),
                'height' => $this->dimension->getHeight(),
            ],
            'distributedPlayers' => $this->distributedPlayers,
        ]));
    }

    public function getDimension(): DimensionInterface
    {
        return $this->dimension;
    }

    public function getCenter(): Point
    {
        return $this->dimension->getCenter();
    }

    public function distributePlayer($teamName)
    {
        $players = $this->getPlayers();
        $team = $players[$teamName];
        foreach ($this->distributedPlayers as $name) {
            if (array_key_exists($name, $team)) {
                unset($team[$name]);
            }
        }
        if (0 == count($team)) {
            return null;
        }
        $playerName = array_rand($team);

        $this->distributedPlayers[] = $playerName;

        $this->save();

        return $playerName;
    }

    private function getPlayers()
    {
        return [
            'blue' => [
                'Cathy Tang',
                'Alyssa Villa',
                'Susie Magana',
                'Kallum May',
                'Ismail Douglas',
                'Tyreece Greaves',
                'Anna-Marie Atherton',
                'Cayden Snow',
                'Ellie-Mae Osborn',
                'Nadia Coates',
                'Akshay Peterson',
                'Bruno Riddle',
                'Tayyib Williams',
                'Phillip Smyth',
                'Makayla Redfern',
                'Lindsey Hope',
                'Camille Harvey',
                'Alannah Ferrell',
                'Kobe Costa',
                'Nabiha Bone',
                'Melissa Joyner',
                'Sharmin Choi',
                'Irene O\'Brien',
                'Greyson Bryan',
                'Lia Stewart',
                'Ross Huff',
                'Allan Kirkland',
                'Amber Kline',
                'Myron Logan',
                'Marianne Mill',
                'Kieron Burke',
                'Rebekka Hendrix',
                'Areebah Burrows',
                'Mohamad Bob',
                'Ayyan Goodman',
                'Asha Weber',
                'Keith Kearney',
                'Harleigh Albert',
                'Sachin Cameron',
                'Orlaith Ewing',
            ],
            'red' => [
                'Reuben Villanueva',
                'Anne Fulton',
                'Daisie Middleton',
                'Lukas Yu',
                'Rihanna Young',
                'Ceri Vickers',
                'Bodhi Hurst',
                'Maleeha Gibson',
                'Ria Sheppard',
                'Mustafa Vincent',
                'Maxim Carty',
                'Macsen Graham',
                'Rikesh Sparrow',
                'Roosevelt Sherman',
                'Carwyn Myers',
                'Shyam Briggs',
                'Anaya Kirby',
                'Ethel Rivers',
                'Tyron Sweet',
                'Anas Paul',
                'Kaiser Mcneil',
                'Tommie Williams',
                'Romilly Mccarty',
                'Teejay O\'Doherty',
                'Alby Oliver',
                'Liberty Mellor',
                'Eileen Downs',
                'Adele English',
                'Halima Charlton',
                'Catrin Fraser',
                'Arran Correa',
                'Kaylie Mccoy',
                'Yusuf Beasley',
                'Stacie Dillard',
                'Lena Cottrell',
                'Bonnie Maxwell',
                'Antonio Cortes',
                'Julian Dale',
                'Youssef Sexton',
                'Tamia Bassett',
            ],
        ];
    }
}
