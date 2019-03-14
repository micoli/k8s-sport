<?php

namespace App\Core\Component\Player\Application\Repository\fs;

use App\Core\Component\Player\Application\Repository\PlayerRepositoryInterface;
use App\Core\Component\Player\Domain\Player;
use App\Core\Port\Persistence\PersistenceServiceInterface;

final class PlayerRepository implements PlayerRepositoryInterface
{
    /** @var PersistenceServiceInterface */
    private $persistenceService;

    public function __construct(PersistenceServiceInterface $persistenceService)
    {
        $this->persistenceService = $persistenceService;
    }

    public function get(): Player
    {
        $player = new Player();
        $player->unserialize($this->persistenceService->get());

        return $player;
    }

    public function update(\Serializable $entity)
    {
        $this->persistenceService->update($entity->serialize());
    }
}
