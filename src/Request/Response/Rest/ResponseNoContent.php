<?php
declare(strict_types=1);

namespace Request\Response\Rest;

class ResponseNoContent extends RestResponseAbstract
{
    protected int $status = 204;
}
