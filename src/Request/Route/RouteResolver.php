<?php
declare(strict_types=1);

namespace Request\Route;

use Autowired\Autowired;
use Autowired\AutowiredHandler;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use Request\Arguments\ArgumentsResolver;
use Request\Attributes\Route;
use Request\Exceptions\InvalidParameterException;
use Request\Response\Response;
use Request\Response\Rest\Entity\BadRequestEntity;
use Request\Response\Rest\ResponseBadRequest;

/**
 * @package Request\Route
 * @author Dennis Munchausen
 */
class RouteResolver
{
    use AutowiredHandler;

    #[Autowired]
    private ArgumentsResolver $argumentResolver;

    public function resolveMatchedRoute(
        \ReflectionMethod $method,
        \ReflectionAttribute $routeAnnotation,
        string $requestUri,
        \ReflectionClass $controllerReflection
    ): ?Response {

        /** @var Route $route */
        $route = $routeAnnotation->newInstance();
        if (!$this->hasMatchedRoute($route, $requestUri)) {
            return null;
        }

        try {
            $arguments = $this->argumentResolver->resolve($method->getParameters(), $route, $requestUri);
            $dispatcher = new Dispatcher($controllerReflection, $method->getName(), $arguments);
            return $dispatcher->dispatch();
        } catch (InvalidParameterException $invalidParameterException) {
            return new ResponseBadRequest(
                new BadRequestEntity(
                    $invalidParameterException->getMessage(),
                    $invalidParameterException->getErrorMap()
                )
            );
        } catch (\ReflectionException $e) {
        }
        return null;
    }

    private function hasMatchedRoute(Route $route, string $requestUri): bool
    {
        $isRouteCorrect = match ($route->getPath()) {
            $requestUri => true,
            default => false
        };

        if (!$isRouteCorrect) {
            $matches = [];
            $pattern = sprintf('/^%s$/', str_replace('/', '\\/' ,$route->getPath()));
            preg_match(
                $pattern,
                $requestUri,
                $matches
            );

            $isRouteCorrect = !empty($matches);
        }

        $isMethodCorrect = match ($route->getMethod()) {
            $_SERVER['REQUEST_METHOD'] => true,
            default => false
        };

        return $isRouteCorrect && $isMethodCorrect;
    }

    public function getDispatcher(
        ReflectionMethod $method,
        ReflectionAttribute $routeAnnotation,
        string $requestUri,
        ReflectionClass $controllerReflection
    ): ?Dispatcher
    {
        /** @var Route $route */
        $route = $routeAnnotation->newInstance();
        if (!$this->hasMatchedRoute($route, $requestUri)) {
            return null;
        }

        try {
            $arguments = $this->argumentResolver->resolve($method->getParameters(), $route, $requestUri);
            return new Dispatcher($controllerReflection, $method->getName(), $arguments);
        } catch (InvalidParameterException $invalidParameterException) {
            // implementation for dispatcher to error route?
        }
        return null;
    }
}
