<?php
declare(strict_types=1);

namespace RequestTest\RequestParameter;

use PHPUnit\Framework\TestCase;
use Request\Response\Rest\Entity\BadRequestEntity;
use Request\Response\Rest\ResponseBadRequest;
use Request\Response\Rest\ResponseOk;
use Request\Response\RestResponse;
use Request\Routing;
use RequestTest\RequestParameter\Examples\ExampleGetParameterEntity;
use RequestTest\RequestParameter\Examples\ExamplePostParameters;
use RequestTest\RequestParameter\Examples\ExampleUriParameterEntity;
use Utils\HasMap;

class PostParameterTest extends TestCase
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
        $routing->registerController(ExamplePostParameters::class);
        $response = $routing->dispatchRoute($requestUri);

        static::assertEquals($expectedResponse, $response);
    }

    public function dataProviderReadSingleGetParametersToSingleVariables(): array
    {
        $errorHashMap = new HasMap();

        $errorHashMap->add('firstPostParameter','Field is required and can´t be empty');

        return [
            'Request with one get parameter' => [
                'expectedResponse' => new ResponseOk(new ExampleGetParameterEntity('hello world')),
                'requestUri' => '/example/uri',
                'setParameters' => function() {
                    $_SERVER["REQUEST_METHOD"] = "POST";
                    $_POST = [
                        'firstPostParameter' => 'hello world'
                    ];
                }
            ],
            'Request with non provided parameter' => [
                'expectedResponse' => new ResponseBadRequest(new BadRequestEntity('Required parameters are missing', $errorHashMap)),
                'requestUri' => '/example/uri',
                'setParameters' => function() {
                    $_SERVER["REQUEST_METHOD"] = "POST";
                    $_POST = [];
                }
            ],
            'Request with one uri parameter' => [
                'expectedResponse' => new ResponseOk(new ExampleUriParameterEntity(1)),
                'requestUri' => '/product/1/preview',
                'setParameters' => function() {
                    $_SERVER["REQUEST_METHOD"] = "POST";
                }
            ]
        ];
    }
}
