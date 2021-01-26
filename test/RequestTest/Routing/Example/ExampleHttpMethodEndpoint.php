<?php
declare(strict_types=1);

namespace RequestTest\Routing;

use Request\Attributes\Route;
use Request\Response\Rest\ResponseAccepted;
use Request\Response\Rest\ResponseCreated;
use Request\Response\Rest\ResponseOk;
use Request\Response\RestResponseEntity;

/**
 * @package RequestTest\Routing
 * @author Dennis Munchausen
 */
class ExampleHttpMethodEndpoint
{
    #[Route("/")]
    public function endpointWithNoParameters(): RestResponseEntity
    {
        return new ResponseOk();
    }

    #[Route("/", Route::HTTP_METHOD_POST)]
    public function aDifferentEndpointWithNoParamters(): RestResponseEntity
    {
        return new ResponseCreated();
    }

    #[Route("/", Route::HTTP_METHOD_DELETE)]
    public function oneMoreEndpointFor(): RestResponseEntity
    {
        return new ResponseAccepted();
    }
}
