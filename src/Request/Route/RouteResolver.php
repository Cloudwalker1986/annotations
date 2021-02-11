<?php
declare(strict_types=1);

namespace Request\Route;

use Autowired\Autowired;
use Autowired\AutowiredHandler;
use Request\Arguments\ArgumentsResolver;
use Request\Attributes\Route;
use Request\Response\Response;

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
        string $requestUri, \ReflectionClass $controllerReflection
    ): ?Response {

        /** @var Route $route */
        $route = $routeAnnotation->newInstance();
        if (!$this->hasMatchedRoute($route, $requestUri)) {
            return null;
        }

        $arguments = $this->argumentResolver->resolve($method->getParameters(), $route, $requestUri);

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
