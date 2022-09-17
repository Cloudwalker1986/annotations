<?php
declare(strict_types=1);

namespace Json\Exception;

use RuntimeException;
use Throwable;

class NotJsonSerializableException extends RuntimeException
{
    public function __construct(int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            'Provided entity does not have an assigned JsonSerializable attribute.',
            $code,
            $previous
        );
    }

}
