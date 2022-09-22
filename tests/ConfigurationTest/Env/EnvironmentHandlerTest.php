<?php
declare(strict_types=1);


namespace ConfigurationTest\Env;


use Autowired\DependencyContainer;
use Configuration\Env\EnvironmentHandler;
use ConfigurationTest\Env\Example\EnvConfig;
use PHPUnit\Framework\TestCase;

class EnvironmentHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function resolve(): void
    {
        DependencyContainer::getInstance()->addCustomHandler(new EnvironmentHandler());
        /** @var EnvConfig $envConfig */
        $envConfig = DependencyContainer::getInstance()->get(EnvConfig::class);

        $this->assertEquals(1, $envConfig->getValueOne());
        $this->assertEquals(2, $envConfig->getValueTwo());
        $this->assertEquals(3, $envConfig->getValueThree());
        $this->assertEquals('Hello World', $envConfig->getWord());
        $this->assertEquals('Should be removed', $envConfig->getSingleQuotes());
        $this->assertEquals([1,2,3,4], $envConfig->getArrayStyle());
    }
}
