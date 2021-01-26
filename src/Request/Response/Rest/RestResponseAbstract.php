<?php
declare(strict_types=1);

namespace Request\Response\Rest;

use Request\Response\RestResponseEntity;

abstract class RestResponseAbstract implements RestResponseEntity
{
    protected int $status;

    protected array $payload = [];

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
