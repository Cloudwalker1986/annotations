<?php
declare(strict_types=1);

namespace Request\Response\Rest;

use BadMethodCallException;
use Request\Response\Rest\Entity\Entity;
use Request\Response\RestResponse;

abstract class RestResponseAbstract implements RestResponse
{
    protected int $status;

    protected ?Entity $entity;

    private bool $paginationEnabled;

    public function __construct(?Entity $entity = null, bool $paginationEnabled = false)
    {
        $this->entity = $entity;
        $this->paginationEnabled = $paginationEnabled;
    }

    public function paginationEnabled(): bool
    {
        return $this->paginationEnabled;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getEntity(): ?Entity
    {
        return $this->entity;
    }

    public function getHtmlContent(): string
    {
        throw new BadMethodCallException('In context of REST API getHtmlContent should never be called.');
    }
}
