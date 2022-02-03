<?php
declare(strict_types=1);

namespace Request\Arguments;

use Autowired\Autowired;
use Autowired\DependencyContainer;
use Autowired\Exception\InterfaceArgumentException;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use JsonException;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;
use Request\Arguments\Json\JsonResolver;
use Request\Attributes\Json\JsonRequest;
use Request\Attributes\Parameters\Parameter;
use Request\Attributes\Route;
use Request\Exceptions\InvalidParameterException;
use Request\Request;
use Throwable;
use Utils\HashMap;
use Utils\Map;

final class ArgumentsResolver
{
    #[Autowired]
    private Request $request;

    #[Autowired]
    private jsonResolver $jsonResolver;

    #[Autowired(concreteClass: DependencyContainer::class, staticFunction: 'getInstance')]
    private DependencyContainer $container;

    /**
     * @throws InterfaceArgumentException
     * @throws InvalidArgumentDefinitionException
     * @throws JsonException
     * @throws ReflectionException
     */
    public function resolve(array $parameters, Route $route, string $requestUri): array
    {
        $arguments = [];
        $errorParameters = new HashMap();

        foreach ($parameters as $parameter) {
            $attributes = $parameter->getAttributes();
            foreach ($attributes as $attribute) {
                /** @var Parameter $attr */
                $attr = $attribute->newInstance();
                $requestParameters = $this->request->getParametersByAttributeType($attr);
                $arguments[] = $this->resolveValues(
                    $parameter,
                    $requestParameters,
                    $route,
                    $requestUri,
                    $errorParameters,
                    $attr->getAlias()
                );
            }
        }

        if ($errorParameters->count() > 0) {
            throw new InvalidParameterException($errorParameters);
        }

        return $arguments;
    }

    /**
     * @throws InterfaceArgumentException
     * @throws InvalidArgumentDefinitionException
     * @throws ReflectionException
     */
    private function resolveValues(
        ReflectionParameter $parameter,
        array $params,
        Route $route,
        string $requestUri,
        Map $errorMap,
        ?string $alias
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
                $requestUri,
                $alias
            );
        }

        if (!$parameter->allowsNull() && $type !== 'bool' && !$object) {
            $errorMap->add($parameter->getName(), 'Field is required and can´t be empty');
        }

        return $object;
    }

    /**
     * @throws InterfaceArgumentException
     * @throws ReflectionException
     */
    private function handleObjectValues(string $object, array $params): object
    {
        $object = $this->container->get($object);

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
                    $alias = null;
                    foreach ($property->getAttributes() as $attribute) {
                        $attr = $attribute->newInstance() ;
                        if ($attr instanceof Parameter && $alias === null) {
                            $alias = $attr->getAlias();
                        }
                    }
                    $type = $property->getType();
                    $val = $params[$alias ?? $property->getName()] ?? null;
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
                $property->setValue($object, $value);
            }

        } catch (ReflectionException | Throwable $e) {
        }

        return $object;
    }

    private function handleNormalType(
        string $type,
        string $key,
        array $params,
        Route $route,
        string $requestUri,
        ?string $alias = null
    ): bool|int|array|float|string {
        $urlValue = $this->urlValue($route, $requestUri);

        $key = $alias ?? $key;

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

    private function urlValue(Route $route, string $requestUri): false|string
    {
        $pattern = sprintf('/^%s$/', str_replace('/', '\\/', $route->getPath()));
        preg_match(
            $pattern,
            $requestUri
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
