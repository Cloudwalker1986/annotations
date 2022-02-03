<?php
declare(strict_types=1);

namespace RequestTest\Routing\Example;

use Autowired\Autowired;
use Request\Attributes\Route;
use Request\Response\Rest\ResponseAccepted;
use Request\Response\Rest\ResponseCreated;
use Request\Response\RestResponse;

/**
 * @package RequestTest\Routing
 * @author Dennis Munchausen
 */
class ExampleHttpMethodEndpoint
{
    #[Autowired]
    private ExampleService $service;

    #[Route("/")]
    public function endpointWithNoParameters(): RestResponse
    {
        return $this->service->render();
    }

    #[Route("/", Route::HTTP_METHOD_POST)]
    public function aDifferentEndpointWithNoParameters(): RestResponse
    {
        return new ResponseCreated();
    }

    #[Route("/", Route::HTTP_METHOD_DELETE)]
    public function oneMoreEndpointFor(): RestResponse
    {
        return new ResponseAccepted();
    }
}
