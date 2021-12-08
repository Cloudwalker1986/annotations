<?php
declare(strict_types=1);

namespace Request\Response\Rest;

class ResponseCreated extends RestResponseAbstract
{
    protected int $status = 201;
}
