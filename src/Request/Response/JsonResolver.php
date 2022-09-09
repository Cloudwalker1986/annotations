<?php
declare(strict_types=1);

namespace Request\Response;

use JetBrains\PhpStorm\ArrayShape;
use JsonException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Request\Attributes\Json\JsonResponse;

class JsonResolver
{
    /**
     * @throws JsonException
     */
    public function toJson(RestResponse $response): string
    {
        try {
            $jsonResponse = [
                'status' => $response->getStatus(),
                'payload' => $this->resolveEntity($response),
            ];
            if ($response->paginationEnabled()) {
                $jsonResponse['pagination'] = $this->resolvePagination($response);
            }
        } catch (ReflectionException $e) {
            $jsonResponse = [
                'status' => 500,
                'payload' => [
                    'errorMessage' => 'Internal Server error'
                ]
            ];
        }

        return json_encode($jsonResponse, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws ReflectionException
     */
    private function resolveEntity(RestResponse $response): ?array
    {
        if ($response->getEntity() === null) {
            return null;
        }

        $entity = $response->getEntity();
        $responseReflection = new ReflectionClass($entity);
        $payload = [];
        $properties = $responseReflection->getProperties();
        array_walk(
            $properties,
            static function(ReflectionProperty $property) use (&$payload, $entity){
            /** @var JsonResponse $jsonResponse */
            $jsonResponse = $property->getAttributes(JsonResponse::class);

            array_walk(
                $jsonResponse,
                static function(ReflectionAttribute $attribute) use ($property, &$payload, $entity) {
                /** @var JsonResponse $attributeObj */
               $attributeObj = $attribute->newInstance();

               if ($attributeObj->shouldIgnore()) {
                   return;
               }
               $field = empty($attributeObj->getAlias()) ? $property->getName() : $attributeObj->getAlias();

               $payload[$field] = $property->getValue($entity);
            });
        });

        return $payload;
    }

    #[ArrayShape(['limit' => "int", 'offset' => "int", 'count' => "int"])]
    private function resolvePagination(RestResponse $response): array
    {
        return [
            'limit' => 0,
            'offset' => 0,
            'count' => 0
        ];
    }

}
