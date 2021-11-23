<?php
declare(strict_types=1);

namespace RequestTest\Routing;

use Autowired\DependencyContainer;
use PHPUnit\Framework\TestCase;
use Request\Attributes\Route;
use Request\Response\Rest\ResponseAccepted;
use Request\Response\Rest\ResponseCreated;
use Request\Response\Rest\ResponseNoContent;
use Request\Response\Rest\ResponseOk;
use Request\Response\RestResponse;
use Request\Routing;
use RequestTest\Routing\Example\ExampleHttpMethodEndpoint;
use RequestTest\Routing\Example\ExampleHttpMethodEntity;
use RequestTest\Routing\Example\ExampleUriEndpoint;

/**
 * @package RequestTest\Routing
 * @author Dennis Munchausen
 */
class RoutingTest extends TestCase
{
    private DependencyContainer $container;

    public function setUp(): void
    {
        $this->container = DependencyContainer::getInstance();
        parent::setUp();
    }

    public function tearDown(): void
    {
        $this->container->flush();
        parent::tearDown();
    }

    /**
     * @dataProvider dataProviderForRouteToEndpointByHttpMethod
     * @test
     *
     * @param RestResponse $expectedResponse
     * @param string $requestUri
     * @param callable $setHttpMethod
     */
    public function routeToEndpointByHttpMethod(
        RestResponse $expectedResponse,
        string $requestUri,
        callable $setHttpMethod
    ) {
        $setHttpMethod();

        $routing = $this->container->get(Routing::class);
        $routing->registerController(ExampleHttpMethodEndpoint::class);
        $routing->registerController(ExampleUriEndpoint::class);
        $response = $routing->dispatchRoute($requestUri);

        $this->assertEquals($expectedResponse, $response);
    }

    public function dataProviderForRouteToEndpointByHttpMethod(): array
    {
        return [
            'Route to "/" by a get request' => [
                'expected' => new ResponseOk(new ExampleHttpMethodEntity()),
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
