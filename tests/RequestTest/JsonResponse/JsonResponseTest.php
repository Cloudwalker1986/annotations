<?php
declare(strict_types=1);

namespace RequestTest\JsonResponse;

use JsonException;
use PHPUnit\Framework\TestCase;
use Request\Response\JsonResolver;
use Request\Response\Rest\ResponseOk;
use RequestTest\JsonResponse\Example\ExampleBigResponseObject;

class JsonResponseTest extends TestCase
{
    /**
     * @test
     *
     * @throws JsonException
     */
    public function jsonResolverWithoutPagination(): void
    {
        $jsonResolver = new JsonResolver();
        $response = new ResponseOk(
            new ExampleBigResponseObject('Hello', 'World', 'Nothing')
        );

        $this->assertEquals(
            json_encode([
                'status' => 200,
                'payload' => [
                    'fieldOne' => 'Hello',
                    'fieldTwo' => 'World'
                ],
            ], JSON_THROW_ON_ERROR),
            $jsonResolver->toJson($response)
        );
    }
    /**
     * @test
     *
     * @throws JsonException
     */
    public function jsonResolverWithPagination(): void
    {
        $jsonResolver = new JsonResolver();
        $response = new ResponseOk(
            new ExampleBigResponseObject('Hello', 'World', 'Nothing'),
            true
        );

        $this->assertEquals(
            json_encode([
                'status' => 200,
                'payload' => [
                    'fieldOne' => 'Hello',
                    'fieldTwo' => 'World'
                ],
                'pagination' => [
                    'limit' => 0,
                    'offset' => 0,
                    'count' => 0
                ]
            ], JSON_THROW_ON_ERROR),
            $jsonResolver->toJson($response)
        );

    }
}
