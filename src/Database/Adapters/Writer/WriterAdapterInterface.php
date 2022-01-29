<?php
declare(strict_types=1);

namespace Database\Adapters\Writer;

use Database\EntityInterface;
use mysqli;
use PDO;

interface WriterAdapterInterface
{
    public function persists(string $query, array $bindParams): int|false;

    public function delete(string $query, array $bindParams): bool;

    public function getConnection(): null|PDO|Mysqli;
}
