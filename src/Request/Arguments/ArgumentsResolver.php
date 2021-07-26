<?php
declare(strict_types=1);

namespace Request\Arguments;

use Autowired\Autowired;
use Autowired\AutowiredHandler;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use ReflectionClass;
use Request\Arguments\Json\JsonResolver;
use Request\Attributes\Json\JsonRequest;
use Request\Attributes\Route;
use Request\Exceptions\InvalidParameterException;
use Request\Request;
use Utils\HasMap;
use Utils\Map;

final class ArgumentsResolver
{
    use AutowiredHandler;

    #[Autowired]
    private Request $request;

    #[Autowired]
    private jsonResolver $jsonResolver;

    /**
     * @throws InvalidArgumentDefinitionException
     * @throws \JsonException
     */
    public function resolve(array $parameters, Route $route, string $requestUri): array
    {
        $arguments = [];
        $errorParameters = new HasMap();

        foreach ($parameters as $parameter) {
            $attributes = $parameter->getAttributes();
            foreach ($attributes as $attribute) {
                $requestParameters = $this->request->getParametersByAttributeType($attribute->newInstance());
                $arguments[] = $this->resolveValues(
                    $parameter,
                    $requestParameters,
                    $route,
                    $requestUri,
                    $errorParameters
                );
            }
        }

        if ($errorParameters->count() > 0) {
            throw new InvalidParameterException($errorParameters);
        }

        return $arguments;
    }

    private function resolveValues(
        \ReflectionParameter $parameter,
        array $params,
        Route $route,
        string $requestUri,
        Map $errorMap
    ): float|array|bool|int|string|object {
        $parameterType = $parameter->getType();
        if ($parameterType === null) {
            throw new InvalidArgumentDefinitionException(
                sprintf(
                    'A type definition for parameter %s is required',
                    $parameter->getName()
                )
            );
        }
        $type = $parameterType->getName();
        if (class_exists($type)) {
            $object = $this->handleObjectValues($type, $params);
        } else {
            $object = $this->handleNormalType(
                $parameterType->getName(),
                str_replace(' ', '', lcfirst($parameter->getName())),
                $params,
                $route,
                $requestUri
            );
        }

        if (!$parameter->allowsNull() && $type !== 'bool' && !$object) {
            $errorMap->add($parameter->getName(), 'Field is required and can´t be empty');
        }

        return $object;
    }

    private function handleObjectValues(string $object, array $params)
    {
        $object = new $object();

        try {
            $reflection = new ReflectionClass($object);

            foreach ($reflection->getProperties() as $property) {
                $jsonRequestAttributes = $property->getAttributes(JsonRequest::class);
                if (isset($jsonRequestAttributes[0])) {
                    /** @var JsonRequest $jsonRequest */
                    $jsonRequest = $jsonRequestAttributes[0]->newInstance();
                    if (!empty($jsonRequest->getClassType())) {
                        return $this->jsonResolver->resolveObject(
                            $jsonRequest,
                            $property,
                            $params,
                            function(string $type, array $internalParams) {
                                return $this->handleObjectValues($type, $internalParams);
                            },
                            $object
                        );
                    }
                    $val = $params[$jsonRequest->getAlias() ?? $property->getName()];
                    $type = $property->getType();
                } else {
                    $type = $property->getType();
                    $val = $params[$property->getName()] ?? null;
                }

                if ($val === null || $type === null) {
                    continue;
                }

                $value = match ($type->getName()) {
                    'string' => $val,
                    'int' => (int) $val,
                    'float' => (float) $val,
                    'double' => (double) $val,
                    'array' => [$val],
                    DateTime::class => new DateTime($val),
                    DateTimeImmutable::class, DateTimeInterface::class => new DateTimeImmutable($val)
                };
                $property->setAccessible(true);
                $property->setValue($object, $value);
            }

        } catch (\ReflectionException | \Throwable $e) {
        }

        return $object;
    }

    private function handleNormalType(
        string $type,
        string $key,
        array $params,
        Route $route,
        string $requestUri
    ): bool|int|array|float|string {
        $urlValue = $this->urlValue($route, $requestUri);

        return match ($type) {
            'int' => (int) ($params[$key] ?? $urlValue),
            'float' => (float) ($params[$key] ?? $urlValue),
            'double' => (double) ($params[$key] ?? $urlValue),
            'bool' => match (strtolower(($params[$key] ?? $urlValue))) {
                '1', 'yes', 'true', 'on' => true,
                default => false

            },
            'array' => explode(',', ($params[$key] ?? $urlValue)),
            default => ($params[$key] ?? $urlValue)
        };
    }

    private function urlValue(Route $route, string $requestUri)
    {
        $matches = [];
        $pattern = sprintf('/^%s$/', str_replace('/', '\\/', $route->getPath()));
        preg_match(
            $pattern,
            $requestUri,
            $matches
        );

        //check if we can use this really!
        $val = explode('/', $requestUri);

        foreach (explode('/', $route->getPath()) as $k => $v) {

            if ($val[$k] === $v) {
                unset($val[$k]);
            }
        }

        return end($val);
    }
}
