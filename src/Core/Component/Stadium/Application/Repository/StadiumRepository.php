<?php

namespace App\Core\Component\Stadium\Application\Repository;

use App\Core\Component\Stadium\Domain\Stadium;
use App\Infrastructure\Persistence\DataInterface;

class StadiumRepository implements StadiumRepositoryInterface
{
    /** @var DataInterface */
    private $data;

    public function __construct(DataInterface $data)
    {
        $this->data = $data;
    }

    public function get(): Stadium
    {
        $stadium = new Stadium();
        $stadium->unserialize($this->data->load());

        return $stadium;
    }

    public function update(\Serializable $entity)
    {
        $this->data->save($entity->serialize());
    }
}
