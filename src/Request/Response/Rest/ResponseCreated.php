<?php
declare(strict_types=1);

namespace Request\Response\Rest;

use Request\Response\RestResponse;

class ResponseCreated extends RestResponseAbstract
{
    protected int $status = 201;
}
