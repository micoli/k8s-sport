<?php

namespace App\Core\Component\Player\Application\Repository;

use App\Core\Component\Player\Domain\Player;
use App\Infrastructure\Persistence\DataInterface;

class PlayerRepository implements PlayerRepositoryInterface
{
    /** @var DataInterface */
    private $data;

    public function __construct(DataInterface $data)
    {
        $this->data = $data;
    }

    public function get(): Player
    {
        $ball = new Player();
        $ball->unserialize($this->data->load());

        return $ball;
    }

    public function update(\Serializable $entity)
    {
        $this->data->save($entity->serialize());
    }
}
