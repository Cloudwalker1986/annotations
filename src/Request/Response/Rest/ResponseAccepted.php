<?php
declare(strict_types=1);

namespace Request\Response\Rest;

use Request\Response\RestResponseEntity;

class ResponseAccepted extends RestResponseAbstract
{
    protected int $status = 202;
}
