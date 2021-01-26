<?php
declare(strict_types=1);

namespace Request\Response\Rest;

use Request\Response\RestResponseEntity;

class ResponseCreated extends RestResponseAbstract
{
    protected int $status = 201;
}
