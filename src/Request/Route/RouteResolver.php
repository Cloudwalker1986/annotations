<?php
declare(strict_types=1);

namespace Request\Route;

use Request\Attributes\Route;
use Request\Response\ResponseEntity;

/**
 * @package Request\Route
 * @author Dennis Munchausen
 */
class RouteResolver
{
    public function resolveMatchedRoute(
        \ReflectionMethod $method,
        \ReflectionAttribute $routeAnnotation,
        string $requestUri, \ReflectionClass $controllerReflection
    ): ?ResponseEntity {
        if (!$this->hasMatchedRoute($routeAnnotation->newInstance(), $requestUri)) {
            return null;
        }

        $arguments = [];

        try {
            return call_user_func_array([$controllerReflection->newInstance(), $method->getName()], $arguments);
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
}
