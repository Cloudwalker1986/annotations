<?php
declare(strict_types=1);

namespace Request\Response\Rest;

use Request\Response\Rest\Entity\Entity;
use Request\Response\RestResponse;

abstract class RestResponseAbstract implements RestResponse
{
    protected int $status;

    protected ?Entity $entity;

    public function __construct(?Entity $entity = null)
    {
        $this->entity = $entity;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getEntity(): ?Entity
    {
        return $this->entity;
    }
}
