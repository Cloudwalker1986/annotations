<?php
declare(strict_types=1);

namespace Event\Listener;

use Autowired\Autowired;
use Autowired\DependencyContainer;
use Event\Attributes\ListenTo;
use Event\DataObject\EventDispatch;
use Event\EventManager;

class Resolver
{
    private const EXCLUDE_DOTS = ['.', '..'];

    #[Autowired]
    private EventManager $eventManager;

    #[Autowired]
    private DependencyContainer $container;

    /**
     * The resolver will recursive over dir entry point
     */
    public function resolve(string $dirEntryPoint)
    {
        $filesAndSubDirs = scandir($dirEntryPoint);

        array_walk($filesAndSubDirs, [$this, 'resolveDir'], $dirEntryPoint);
    }

    public function resolveDir(string $value, int $index, string $parentDir)
    {
        if (in_array($value, static::EXCLUDE_DOTS)) {
            return;
        }

        if (str_contains($value, '.php')) {

            $namespace = $this->extractNamespace($parentDir . DIRECTORY_SEPARATOR . $value);

            $classReflection = new \ReflectionClass(sprintf(
                '%s\%s',
                $namespace,
                str_replace(['.phpt', '.php'], '', $value)
            ));

            $this->checkForListingTo($classReflection);
            return;
        }
        $this->resolve($parentDir . DIRECTORY_SEPARATOR . $value);
    }

    private function extractNamespace(string $file)
    {
        $ns = null;
        $handle = fopen($file, 'rb');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (str_starts_with($line, 'namespace')) {
                    $parts = explode(' ', $line);
                    $ns = rtrim(trim($parts[1]), ';');
                    break;
                }
            }
            fclose($handle);
        }
        return $ns;
    }

    private function checkForListingTo(\ReflectionClass $classReflection)
    {
        foreach ($classReflection->getMethods() as $method) {
            $methodReflection = $classReflection->getMethod($method->getName());
            foreach ($methodReflection->getAttributes(ListenTo::class) as $attribute) {
                /** @var ListenTo $listener */
                $listener = $attribute->newInstance();
                $this->eventManager->addListener(
                    $listener->getEventName(),
                    new EventDispatch($listener->getListener(), $method->getName())
                );
            }
        }
    }
}
