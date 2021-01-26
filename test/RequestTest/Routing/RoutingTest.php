<?php
declare(strict_types=1);

namespace RequestTest\Routing;

use PHPUnit\Framework\TestCase;
use Request\Attributes\Route;
use Request\Response\Rest\ResponseAccepted;
use Request\Response\Rest\ResponseCreated;
use Request\Response\Rest\ResponseNoContent;
use Request\Response\Rest\ResponseOk;
use Request\Response\RestResponseEntity;
use Request\Routing;
use RequestTest\Routing\Example\ExampleUriEndpoint;

/**
 * @package RequestTest\Routing
 * @author Dennis Munchausen
 */
class RoutingTest extends TestCase
{
    /**
     * @dataProvider dataProviderForRouteToEndpointByHttpMethod
     * @test
     *
     * @param RestResponseEntity $expectedResponse
     * @param string $requestUri
     * @param callable $setHttpMethod
     */
    public function routeToEndpointByHttpMethod(
        RestResponseEntity $expectedResponse,
        string $requestUri,
        callable $setHttpMethod
    ) {
        $setHttpMethod();

        $routing = new Routing();
        $routing->registerController(ExampleHttpMethodEndpoint::class);
        $routing->registerController(ExampleUriEndpoint::class);
        $response = $routing->dispatchRoute($requestUri);

        $this->assertEquals($expectedResponse, $response);
    }

    public function dataProviderForRouteToEndpointByHttpMethod(): array
    {
        return [
            'Route to "/" by a get request' => [
                'expected' => new ResponseOk(),
                'requestUri' => '/',
                'setHttpMethod' => function() {
                    $_SERVER['REQUEST_METHOD'] = Route::HTTP_METHOD_GET;
                }
            ],
            'Route to "/" by a post request' => [
                'expected' => new ResponseCreated(),
                'requestUri' => '/',
                'setHttpMethod' => function() {
                    $_SERVER['REQUEST_METHOD'] = Route::HTTP_METHOD_POST;
                }
            ],
            'Route to "/" by a delete request' => [
                'expected' => new ResponseAccepted(),
                'requestUri' => '/',
                'setHttpMethod' => function() {
                    $_SERVER['REQUEST_METHOD'] = Route::HTTP_METHOD_DELETE;
                }
            ],
            'Route to /product/1/edit with patch request ' => [
                'expected' => new ResponseNoContent(),
                'requestUri' => '/product/1/edit',
                'setHttpMethod' => function() {
                    $_SERVER['REQUEST_METHOD'] = Route::HTTP_METHOD_PATCH;
                }
            ]
        ];
    }


}
