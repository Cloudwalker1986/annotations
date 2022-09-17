<?php
declare(strict_types=1);

namespace Request;

use Autowired\Autowired;
use Request\Attributes\Route;
use Request\Response\Response;
use Request\Response\RestResponse;
use Request\Route\Dispatcher;
use Request\Route\RouteResolver;

/**
 * @package Request
 * @author Dennis Munchausen
 */
final class Routing
{
    #[Autowired]
    private RouteResolver $routeResolver;

    private array $registeredController;

    /**
     * @throws \Autowired\Exception\InterfaceArgumentException
     * @throws Arguments\InvalidArgumentDefinitionException
     * @throws \JsonException
     */
    public function createDispatcher(string $requestUri): Dispatcher
    {
        $positionOfGetParams = mb_strpos($requestUri, "?");

        if ($positionOfGetParams !== false && $positionOfGetParams >= 0) {
            $requestUri = mb_substr($requestUri, 0, $positionOfGetParams);
        }

        foreach ($this->registeredController as $controller) {
            try {
                $controllerReflection = new \ReflectionClass($controller);

                foreach ($controllerReflection->getMethods() as $method) {
                    if (!$method->isPublic()) {
                        continue;
                    }

                    $routeAnnotation = $method->getAttributes(Route::class)[0] ?? null;

                    if (null === $routeAnnotation) {
                        continue;
                    }

                    $dispatcher = $this->routeResolver->getDispatcher(
                        $method,
                        $routeAnnotation,
                        $requestUri,
                        $controllerReflection
                    );

                    if ($dispatcher === null) {
                        continue;
                    }

                    return $dispatcher;
                }
            } catch (\ReflectionException $e) {
            }
        }
        //call here an default error controller
        throw new \RuntimeException('Unable to route');

    }

    /**
     * @throws \Autowired\Exception\InterfaceArgumentException
     * @throws Arguments\InvalidArgumentDefinitionException
     * @throws \JsonException
     */
    public function dispatchRoute(string $requestUri): Response|RestResponse
    {
        $positionOfGetParams = mb_strpos($requestUri, "?");

        if ($positionOfGetParams !== false && $positionOfGetParams >= 0) {
            $requestUri = mb_substr($requestUri, 0, $positionOfGetParams);
        }

        foreach ($this->registeredController as $controller) {
            try {
                $controllerReflection = new \ReflectionClass($controller);

                foreach ($controllerReflection->getMethods() as $method) {
                    if (!$method->isPublic()) {
                        continue;
                    }

                    $routeAnnotation = $method->getAttributes(Route::class)[0] ?? null;

                    if (null === $routeAnnotation) {
                        continue;
                    }

                    $response = $this->routeResolver->resolveMatchedRoute(
                        $method,
                        $routeAnnotation,
                        $requestUri,
                        $controllerReflection
                    );

                    if ($response === null) {
                        continue;
                    }

                    return $response;
                }
            } catch (\ReflectionException $e) {
            }
        }
        //call here an default error controller
        throw new \RuntimeException('Unable to route');
    }

    public function registerController(string $className): void
    {
        $this->registeredController[] = $className;
    }
}
