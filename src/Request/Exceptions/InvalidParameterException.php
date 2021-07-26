<?php
declare(strict_types=1);

namespace Request\Exceptions;

use Utils\Map;
use InvalidArgumentException;

class InvalidParameterException extends InvalidArgumentException
{
    public function __construct(private Map $errorMap)
    {
        parent::__construct('Required parameters are missing', 400);
    }

    public function getErrorMap(): Map
    {
        return $this->errorMap;
    }
}
