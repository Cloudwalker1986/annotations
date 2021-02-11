<?php
declare(strict_types=1);

namespace Request\Arguments;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Request\Attributes\GetParameter;
use Request\Attributes\Route;

final class ArgumentsResolver
{
    public function resolve(array $parameters, Route $route, string $requestUri): array
    {
        $arguments = [];

        foreach ($parameters as $parameter) {
            $attributes = $parameter->getAttributes();
            foreach ($attributes as $attribute) {
                if (GetParameter::class === $attribute->getName()) {
                    $arguments[] = $this->resolveValues($parameter, $_GET, $route, $requestUri);
                }
            }
        }
        return $arguments;
    }

    private function resolveValues(
        \ReflectionParameter $parameter,
        array $getParams,
        Route $route,
        string $requestUri
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
            $object = $this->handleObjectValues($type, $getParams);
        } else {
            $object = $this->handleNormalType(
                $parameterType->getName(),
                str_replace(' ', '', lcfirst($parameter->getName())),
                $getParams,
                $route,
                $requestUri
            );
        }

        return $object;
    }

    private function handleObjectValues(string $object, array $getParams)
    {
        $object = new $object();
        try {
            $reflection = new \ReflectionClass($object);

            foreach ($reflection->getProperties() as $property) {
                $type = $property->getType();
                $val = $getParams[$property->getName()] ?? null;

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
        array $getParams,
        Route $route,
        string $requestUri
    ): bool|int|array|float|string {
        $urlValue = $this->urlValue($route, $requestUri);

        return match ($type) {
            'int' => (int) ($getParams[$key] ?? $urlValue),
            'float' => (float) ($getParams[$key] ?? $urlValue),
            'double' => (double) ($getParams[$key] ?? $urlValue),
            'bool' => match (strtolower(($getParams[$key] ?? $urlValue))) {
                '1', 'yes', 'true', 'on' => true,
                default => false

            },
            'array' => explode(',', ($getParams[$key] ?? $urlValue)),
            default => ($getParams[$key] ?? $urlValue)
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
