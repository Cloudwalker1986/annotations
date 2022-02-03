<?php
declare(strict_types=1);

namespace Database\Adapters\Reader;

use mysqli;
use PDO;

interface ReaderAdapterInterface
{
    public function fetchRow(string $query, array $bindingParameters);

    public function fetchAll(string $query, array $bindingParameters);

    public function getConnection(): null|PDO|Mysqli;
}
