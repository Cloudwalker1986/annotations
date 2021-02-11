<?php
declare(strict_types=1);

namespace RequestTest\RequestParameter;

use PHPUnit\Framework\TestCase;
use Request\Response\Rest\ResponseOk;
use Request\Response\RestResponse;
use Request\Routing;
use RequestTest\RequestParameter\Examples\ExampleGetParameters;
use RequestTest\RequestParameter\Examples\ExampleGetParameterEntity;
use RequestTest\RequestParameter\Examples\ExampleUriParameterEntity;

class GetParameterTest extends TestCase
{
    /**
     * @dataProvider dataProviderReadSingleGetParametersToSingleVariables
     * @test
     *
     * @param RestResponse $expectedResponse
     * @param string $requestUri
     * @param callable $setParameters
     */
    public function readSingleGetParametersToSingleVariables(
        RestResponse $expectedResponse,
        string $requestUri,
        callable $setParameters
    ): void
    {
        $setParameters();
        $routing = new Routing();
        $routing->registerController(ExampleGetParameters::class);
        $response = $routing->dispatchRoute($requestUri);

        static::assertEquals($expectedResponse, $response);
    }

    public function dataProviderReadSingleGetParametersToSingleVariables(): array
    {
        return [
            'Request with one get parameter' => [
                'expectedResponse' => new ResponseOk(new ExampleGetParameterEntity('hello world')),
                'requestUri' => '/example/uri?first-get-parameter=hello world',
                'setParameters' => function() {
                    $_SERVER["REQUEST_METHOD"] = "GET";
                    $_GET = [
                        'firstGetParameter' => 'hello world'
                    ];
                }
            ],
            'Request with one uri parameter' => [
                'expectedResponse' => new ResponseOk(new ExampleUriParameterEntity(1)),
                'requestUri' => '/product/1/preview',
                'setParameters' => function() {
                    $_SERVER["REQUEST_METHOD"] = "GET";
                }
            ]
        ];
    }
}
