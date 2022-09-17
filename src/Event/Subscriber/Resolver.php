<?php
declare(strict_types=1);

namespace Event\Subscriber;

use Autowired\Autowired;
use Autowired\DependencyContainer;
use Event\Attributes\SubscribeTo;
use Event\DataObject\EventSubscriber;
use Event\EventManager;
use ReflectionClass;
use ReflectionException;

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
    public function resolve(string $dirEntryPoint): void
    {
        $filesAndSubDirs = scandir($dirEntryPoint);

        array_walk($filesAndSubDirs, [$this, 'resolveDir'], $dirEntryPoint);
    }

    /**
     * @throws ReflectionException
     */
    public function resolveDir(string $value, int $index, string $parentDir): void
    {
        if (in_array($value, static::EXCLUDE_DOTS)) {
            return;
        }

        if (str_contains($value, '.php')) {

            $namespace = $this->extractNamespace($parentDir . DIRECTORY_SEPARATOR . $value);

            $classReflection = new ReflectionClass(sprintf(
                '%s\%s',
                $namespace,
                str_replace(['.phpt', '.php'], '', $value)
            ));

            $this->checkForListingTo($classReflection);
            return;
        }
        $this->resolve($parentDir . DIRECTORY_SEPARATOR . $value);
    }

    private function extractNamespace(string $file): ?string
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

    /**
     * @throws ReflectionException
     */
    private function checkForListingTo(ReflectionClass $classReflection): void
    {
        foreach ($classReflection->getMethods() as $method) {
            $methodReflection = $classReflection->getMethod($method->getName());
            foreach ($methodReflection->getAttributes(SubscribeTo::class) as $attribute) {
                /** @var SubscribeTo $subscriber */
                $subscriber = $attribute->newInstance();
                $this->eventManager->addSubscriber(
                    $subscriber->getEventName(),
                    new EventSubscriber($subscriber->getSubscriber(), $method->getName())
                );
            }
        }
    }
}
