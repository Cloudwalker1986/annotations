<?php
declare(strict_types=1);

namespace RequestTest\Routing\Example;

use Request\Attributes\Route;
use Request\Response\Rest\Entity;
use Request\Response\Rest\ResponseAccepted;
use Request\Response\Rest\ResponseCreated;
use Request\Response\Rest\ResponseOk;
use Request\Response\RestResponse;

/**
 * @package RequestTest\Routing
 * @author Dennis Munchausen
 */
class ExampleHttpMethodEndpoint
{
    #[Route("/")]
    public function endpointWithNoParameters(): RestResponse
    {
        return new ResponseOk(new ExampleHttpMethodEntity());
    }

    #[Route("/", Route::HTTP_METHOD_POST)]
    public function aDifferentEndpointWithNoParamters(): RestResponse
    {
        return new ResponseCreated();
    }

    #[Route("/", Route::HTTP_METHOD_DELETE)]
    public function oneMoreEndpointFor(): RestResponse
    {
        return new ResponseAccepted();
    }
}
