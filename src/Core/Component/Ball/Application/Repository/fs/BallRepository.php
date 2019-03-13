<?php

namespace App\Core\Component\Ball\Application\Repository\fs;

use App\Core\Component\Ball\Application\Repository\BallRepositoryInterface;
use App\Core\Component\Ball\Domain\Ball;
use App\Core\Port\Persistence\PersistenceServiceInterface;

final class BallRepository implements BallRepositoryInterface
{
    /** @var PersistenceServiceInterface */
    private $persistenceService;

    public function __construct(PersistenceServiceInterface $persistenceService)
    {
        $this->persistenceService = $persistenceService;
    }

    public function get(): Ball
    {
        $ball = new Ball();
        $ball->unserialize($this->persistenceService->get());

        return $ball;
    }

    public function update(\Serializable $entity)
    {
        $this->persistenceService->update($entity->serialize());
    }
}
