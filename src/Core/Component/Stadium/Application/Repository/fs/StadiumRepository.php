<?php

namespace App\Core\Component\Stadium\Application\Repository\fs;

use App\Core\Component\Stadium\Application\Repository\StadiumRepositoryInterface;
use App\Core\Component\Stadium\Domain\Stadium;
use App\Core\Port\Persistence\PersistenceServiceInterface;

final class StadiumRepository implements StadiumRepositoryInterface
{
    /** @var PersistenceServiceInterface */
    private $persistenceService;

    public function __construct(PersistenceServiceInterface $persistenceService)
    {
        $this->persistenceService = $persistenceService;
    }

    public function get(): Stadium
    {
        $stadium = new Stadium();
        $stadium->unserialize($this->persistenceService->get());

        return $stadium;
    }

    public function update(\Serializable $entity)
    {
        $this->persistenceService->update($entity->serialize());
    }
}
