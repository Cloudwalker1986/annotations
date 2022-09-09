<?php
declare(strict_types=1);

namespace Database\Parameters;

class Pagination
{
    public function __construct(private readonly int $limit, private readonly int $offset){}

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }
}
