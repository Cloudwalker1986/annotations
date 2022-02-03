<?php
declare(strict_types=1);

namespace RequestTest\Routing\Example;

use Request\Response\Rest\ResponseOk;
use Request\Response\RestResponse;

class ExampleService
{
    public function render(): RestResponse
    {
        return new ResponseOk(new ExampleHttpMethodEntity());
    }
}
