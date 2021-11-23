<?php
declare(strict_types=1);

namespace RequestTest\JsonRequest;

use Autowired\Cache\CachingService;
use Autowired\DependencyContainer;
use Closure;
use PHPUnit\Framework\TestCase;
use Request\Attributes\Parameters\Parameter;
use Request\Request;
use Request\Routing;
use RequestTest\Example\ExampleJsonRawRequestParameters;
use RequestTest\Example\RootCollectionObject;
use RequestTest\Example\RootMapObject;
use RequestTest\Example\SubObjectOne;
use Utils\HasMap;
use Utils\ListCollection;

class JsonRequestTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider jsonRawRequestParametersDataProvider
     *
     * @param Closure $setParameters
     * @param string $path
     * @param Request $mockedRequest
     */
    public function jsonRawRequestParameters(
        Closure $setParameters,
        string $path,
        Request $mockedRequest,
        object $expected
    ): void
    {
        $setParameters();
        DependencyContainer::getInstance()->set(Request::class, $mockedRequest);
        /** @var Routing $routing */
        $routing = DependencyContainer::getInstance()->get(Routing::class);
        $routing->registerController(ExampleJsonRawRequestParameters::class);
        $dispatcher = $routing->createDispatcher($path);

        $actual = $dispatcher->getParameters()[0];

        $this->assertEquals($expected, $actual);
        DependencyContainer::getInstance()->flush();
    }

    public function jsonRawRequestParametersDataProvider()
    {
        $collectionRequest = new class() extends Request {
            public function getParametersByAttributeType(Parameter $parameter): array
            {
                return [
                    'fieldOne' => 'Root object field one',
                    'fieldTwoAlias' => 'Root object field two',
                    'items' => [
                        [
                            'subFieldOne' => 'SubObjectOneFieldOne',
                            'subFieldTwoAlias' => 'SubObjectOneFieldTwo',
                        ],
                        [
                            'subFieldOne' => 'SubObjectTwoFieldOne',
                            'subFieldTwoAlias' => 'SubObjectTwoFieldTwo',
                        ],
                    ]
                ];
            }
        };
        $mapRequest = new class() extends Request {
            public function getParametersByAttributeType(Parameter $parameter): array
            {
                return [
                    'fieldOne' => 'Root object field one',
                    'fieldTwoAlias' => 'Root object field two',
                    'items' => [
                        'mapFieldOne' => [
                            'subFieldOne' => 'SubObjectOneFieldOne',
                            'subFieldTwoAlias' => 'SubObjectOneFieldTwo',
                        ],
                        'mapFieldTwo' => [
                            'subFieldOne' => 'SubObjectTwoFieldOne',
                            'subFieldTwoAlias' => 'SubObjectTwoFieldTwo',
                        ],
                    ]
                ];
            }
        };

        $subObjOne = new SubObjectOne('SubObjectOneFieldOne', 'SubObjectOneFieldTwo');
        $subObjTwo = new SubObjectOne('SubObjectTwoFieldOne', 'SubObjectTwoFieldTwo');
        $list = new ListCollection();
        $hasMap = new HasMap();

        $list->add($subObjOne)->add($subObjTwo);
        $hasMap->add('mapFieldOne', $subObjOne)->add('mapFieldTwo', $subObjTwo);

        $collectionExpected = new RootCollectionObject(
            'Root object field one',
            'Root object field two',
            $list
        );
        $mapExpected = new RootMapObject(
            'Root object field one',
            'Root object field two',
            $hasMap
        );

        return [
            'json raw request parameters with collection' => [
                'setParameter' => function () {
                    $_SERVER['REQUEST_METHOD'] = 'POST';
                },
                'path' => '/collection-test',
                'mockedRequest' => $collectionRequest,
                'expected' => $collectionExpected
            ],
            'json raw request parameters with map' => [
                'setParameter' => function () {
                    $_SERVER['REQUEST_METHOD'] = 'POST';
                },
                'path' => '/map-test',
                'mockedRequest' => $mapRequest,
                'expected' => $mapExpected,
            ]
        ];
    }
}
