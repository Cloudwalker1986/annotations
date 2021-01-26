<?php
declare(strict_types=1);

namespace Request\Response\Rest;

class ResponseOk extends RestResponseAbstract
{
    protected int $status = 200;
}
