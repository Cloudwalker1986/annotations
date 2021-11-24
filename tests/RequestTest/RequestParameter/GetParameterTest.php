<?php
declare(strict_types=1);

namespace RequestTest\RequestParameter;

use Autowired\DependencyContainer;
use PHPUnit\Framework\TestCase;
use Request\Response\Rest\Entity\BadRequestEntity;
use Request\Response\Rest\ResponseBadRequest;
use Request\Response\Rest\ResponseOk;
use Request\Response\RestResponse;
use Request\Routing;
use RequestTest\RequestParameter\Examples\ExampleGetParameters;
use RequestTest\RequestParameter\Examples\ExampleGetParameterEntity;
use RequestTest\RequestParameter\Examples\ExampleUriParameterEntity;
use Utils\HasMap;

class GetParameterTest extends TestCase
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
        /** @var Routing $routing */
        $routing = $this->container->get(Routing::class);
        $routing->registerController(ExampleGetParameters::class);
        $response = $routing->dispatchRoute($requestUri);

        static::assertEquals($expectedResponse, $response);
    }

    public function dataProviderReadSingleGetParametersToSingleVariables(): array
    {
        $errorHashMap = new HasMap();

        $errorHashMap->add('firstGetParameter','Field is required and can´t be empty');

        return [
            'Request with one get parameter' => [
                'expectedResponse' => new ResponseOk(new ExampleGetParameterEntity('hello world')),
                'requestUri' => '/example/uri?first-get-parameter=hello world',
                'setParameters' => function() {
                    $_SERVER["REQUEST_METHOD"] = "GET";
                    $_GET = [
                        'first-get-parameter' => 'hello world'
                    ];
                }
            ],
            'Request with non provided parameter' => [
                'expectedResponse' => new ResponseBadRequest(new BadRequestEntity('Required parameters are missing', $errorHashMap)),
                'requestUri' => '/example/uri',
                'setParameters' => function() {
                    $_SERVER["REQUEST_METHOD"] = "GET";
                    $_GET = [];
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
