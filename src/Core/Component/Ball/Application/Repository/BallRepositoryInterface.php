<?php

namespace App\Core\Component\Ball\Application\Repository;

use App\Core\Component\Ball\Domain\Ball;

interface BallRepositoryInterface
{
    public function get(): Ball;

    public function update(\Serializable $entity);
}
