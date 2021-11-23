<?php
declare(strict_types=1);

namespace DatabaseTest\Query;

use Autowired\DependencyContainer;
use Autowired\Exception\InterfaceArgumentException;
use Configuration\ConfigurationHandler;
use Database\Autowired\AutowiredHandler;
use Database\Reader\PdoReader;
use DatabaseTest\Example\ExampleService;
use DatabaseTest\Example\UserEntity;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Utils\ListCollection;

class BaseRepositoryTest extends TestCase
{
    private PdoReader $pdoReader;

    /**
     * @throws InterfaceArgumentException
     * @throws ReflectionException
     */
    public static function setUpBeforeClass(): void
    {
        DependencyContainer::getInstance()->addInterfaceHandler(
            DependencyContainer::getInstance()->get(AutowiredHandler::class)
        );
        DependencyContainer::getInstance()->addCustomHandler(
            DependencyContainer::getInstance()->get(ConfigurationHandler::class)
        );
        parent::setUpBeforeClass();
    }

    protected function setUp(): void
    {
        $this->getPdoReader()->getConnection()->exec(
            <<<'TAG'
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `user_email_uindex` (`email`),
  UNIQUE KEY `user_name_uindex` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

TAG
        );
        $this->getPdoReader()->getConnection()->exec(
            <<<'TAG'
INSERT INTO `user` (`id_user`, `name`, `email`) 
    VALUES  
        (1, "test", "test@test.de"), 
        (2, "test2", "test2@test.de"),
        (3, "SomeOther", "other@other.de")
TAG
);
        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->getPdoReader()->getConnection()->exec('DROP TABLE user');
        parent::tearDown();
    }


    /**
     * @test
     */
    public function findUserByCustomValueSearch(): void
    {
        $svc = new ExampleService();

        $entity = $svc->findUser('test');

        $this->assertInstanceOf(UserEntity::class, $entity);
    }

    /**
     * @test
     */
    public function findUsersByCustomValueSearch(): void
    {
        $svc = new ExampleService();

        $collection = new ListCollection();
        $collection
            ->add(new UserEntity(1, 'test', 'test@test.de'))
            ->add(new UserEntity(2, 'test2', 'test2@test.de'));
        $this->assertEquals($collection, $svc->findAll());
    }

    /**
     * @test
     */
    public function findUsersBySomeCustomSearch(): void
    {
        $svc = new ExampleService();

        $collection = new ListCollection();
        $collection
            ->add(new UserEntity(3, 'SomeOther', 'other@other.de'));
        $this->assertEquals($collection, $svc->findBySomeCustomSearch());
    }

    /**
     * @test
     */
    public function geUserByPagination(): void
    {
        $svc = new ExampleService();

        $collection = new ListCollection();
        $collection
            ->add(new UserEntity(2, 'test2', 'test2@test.de'));
        $this->assertEquals($collection, $svc->findByPagination());
    }

    private function getPdoReader(): PdoReader
    {
        if (empty($this->pdoReader)) {
            $this->pdoReader = DependencyContainer::getInstance()->get(PdoReader::class);
            $this->pdoReader->init();
        }
        return $this->pdoReader;
    }
}