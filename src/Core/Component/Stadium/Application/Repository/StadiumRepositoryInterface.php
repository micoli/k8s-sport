<?php

namespace App\Core\Component\Stadium\Application\Repository;

use App\Core\Component\Stadium\Domain\Stadium;

interface StadiumRepositoryInterface
{
    public function get(): Stadium;

    public function update(\Serializable $entity);
}
