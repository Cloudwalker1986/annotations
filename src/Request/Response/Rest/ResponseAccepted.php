<?php
declare(strict_types=1);

namespace Request\Response\Rest;

class ResponseAccepted extends RestResponseAbstract
{
    protected int $status = 202;
}
