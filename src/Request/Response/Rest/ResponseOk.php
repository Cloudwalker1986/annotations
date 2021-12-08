<?php
declare(strict_types=1);

namespace Request\Response\Rest;

use Request\Response\Rest\Entity\Entity;

class ResponseOk extends RestResponseAbstract
{
    protected int $status = 200;

    public function __construct(Entity $entity, bool $paginationEnabled = false)
    {
        parent::__construct($entity, $paginationEnabled);
    }
}
