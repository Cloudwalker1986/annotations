<?php
declare(strict_types=1);

namespace ConfigurationTest;

use Autowired\DependencyContainer;
use Configuration\ConfigurationHandler;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @test
     */
    public function parseConfig(): void
    {
        DependencyContainer::getInstance()->addCustomHandler(new ConfigurationHandler());
        /** @var ConfigExample $config */
        $config = DependencyContainer::getInstance()->get(ConfigExample::class);

        $this->assertEquals('password', $config->getPassword());
        $this->assertEquals('root', $config->getUser());
        $this->assertEquals('db_test', $config->getDatabase());
    }
}
