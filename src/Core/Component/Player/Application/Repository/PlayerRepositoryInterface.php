<?php

namespace App\Core\Component\Player\Application\Repository;

use App\Core\Component\Player\Domain\Player;

interface PlayerRepositoryInterface
{
    public function get(): Player;

    public function update(\Serializable $entity);
}
