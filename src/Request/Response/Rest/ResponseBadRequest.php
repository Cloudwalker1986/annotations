<?php
declare(strict_types=1);

namespace Request\Response\Rest;

use Request\Response\Rest\Entity\Entity;

class ResponseBadRequest extends RestResponseAbstract
{
    protected int $status = 400;

    public function __construct(Entity $entity)
    {
        parent::__construct($entity);
    }
}
