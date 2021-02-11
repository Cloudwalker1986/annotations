<?php
declare(strict_types=1);

namespace Request\Response\Rest;

class ResponseOk extends RestResponseAbstract
{
    protected int $status = 200;

    public function __construct(Entity $entity)
    {
        parent::__construct($entity);
    }
}
