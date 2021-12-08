<?php
declare(strict_types=1);

namespace RequestTest\RequestParameter\Examples;

use Request\Attributes\Parameters\PostParameter;
use Request\Attributes\Route;
use Request\Response\Rest\ResponseOk;
use Request\Response\RestResponse;

class ExamplePostParameters
{
    #[Route('/example/uri', Route::HTTP_METHOD_POST)]
    public function myExpectedRouteWithGetParameter(#[PostParameter] string $firstPostParameter): RestResponse
    {
        return new ResponseOk(new ExampleGetParameterEntity($firstPostParameter));
    }

    #[Route('/product/\d/preview', Route::HTTP_METHOD_POST)]
    public function myExpectedRouteWithUriParams(#[PostParameter] int $someId): RestResponse {
        return new ResponseOk(new ExampleUriParameterEntity($someId));
    }

    #[Route('/products', Route::HTTP_METHOD_POST)]
    public function myExpectedRouteWithGetParametersCaseOne(
        #[PostParameter] string $firstParameter,
        #[PostParameter] string $secondParameter
    ): RestResponse
    {
        return new ResponseOk(new ExampleGetParametersEntity($firstParameter, $secondParameter));
    }

    #[Route('/product/create', Route::HTTP_METHOD_POST)]
    public function objectParameterAssignmentFromPostRequest(
        #[PostParameter] ExamplePostObjectParameter $params
    ): RestResponse
    {
        return new ResponseOk(new ExamplePostObjectParameterEntity(
            $params->getParameterOne(),
            $params->getParameterTwo(),
            $params->getParameterAlias()
        ));
    }
}
