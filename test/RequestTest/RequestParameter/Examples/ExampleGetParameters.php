<?php
declare(strict_types=1);

namespace RequestTest\RequestParameter\Examples;

use Request\Attributes\GetParameter;
use Request\Attributes\Route;
use Request\Response\Rest\Entity;
use Request\Response\Rest\ResponseOk;
use Request\Response\RestResponse;

class ExampleGetParameters
{
    #[Route('/example/uri')]
    public function myExpectedRouteWithGetParameter(#[GetParameter] string $firstGetParameter): RestResponse
    {
        return new ResponseOk(new ExampleGetParameterEntity($firstGetParameter));
    }

    #[Route('/product/\d/preview')]
    public function myExpectedRouteWithUriParams(#[GetParameter] int $someId): RestResponse {
        return new ResponseOk(new ExampleUriParameterEntity($someId));
    }

    #[Route('/products')]
    public function myExpectedRouteWithGetParametersCaseOne(
        #[GetParameter] string $firstParameter,
        #[GetParameter] string $secondParameter
    ): RestResponse
    {
        return new ResponseOk(new ExampleGetParametersEntity($firstParameter, $secondParameter));
    }
}
