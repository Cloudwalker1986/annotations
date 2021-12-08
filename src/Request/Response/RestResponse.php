<?php
declare(strict_types=1);

namespace Request\Response;

use Request\Response\Rest\Entity\Entity;

interface RestResponse extends Response
{
    public function getStatus(): int;

    public function getEntity(): ?Entity;

    public function paginationEnabled(): bool;
}
