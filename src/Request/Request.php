<?php
declare(strict_types=1);

namespace Request;

use Request\Attributes\Parameters\Parameter;

class Request
{
    /**
     * @throws \JsonException
     */
    public function getParametersByAttributeType(Parameter $parameter): array
    {
        if ($parameter->isGet()) {
            return $_GET;
        } elseif ($parameter->isPost()) {
            return $_POST;
        } elseif ($parameter->isRawBody()) {
            return json_decode(
                file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR
            );
        }

        return [];
    }
}
