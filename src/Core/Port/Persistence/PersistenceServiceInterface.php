<?php

namespace App\Core\Port\Persistence;

interface PersistenceServiceInterface
{
    public function get(): string;

    public function update(string $entity);
}
