<?php

namespace App\Core\Component\Ball\Application\Repository;

use App\Core\Component\Ball\Domain\Ball;
use App\Infrastructure\Persistence\DataInterface;

class BallRepository implements BallRepositoryInterface
{
    /** @var DataInterface */
    private $data;

    public function __construct(DataInterface $data)
    {
        $this->data = $data;
    }

    public function get(): Ball
    {
        $ball = new Ball();
        $ball->unserialize($this->data->load());

        return $ball;
    }

    public function update(\Serializable $entity)
    {
        $this->data->save($entity->serialize());
    }
}
