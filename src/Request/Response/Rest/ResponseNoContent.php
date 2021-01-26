<?php
declare(strict_types=1);

namespace Request\Response\Rest;

/**
 * @package Request\Response\Rest
 * @author Dennis Munchausen
 */
class ResponseNoContent extends RestResponseAbstract
{
    protected int $status = 204;
}
