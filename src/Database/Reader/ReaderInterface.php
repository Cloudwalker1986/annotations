<?php
declare(strict_types=1);

namespace Database\Reader;

interface ReaderInterface
{
    public function fetchRow(string $query, array $bindingParameters);

    public function fetchAll(string $query, array $bindingParameters);
}
