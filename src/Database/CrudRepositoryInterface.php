<?php
declare(strict_types=1);

namespace Database;

interface CrudRepositoryInterface
{
    public function persists(EntityInterface $entity): EntityInterface;

    public function delete(EntityInterface $entity): bool;
}
