<?php
declare(strict_types=1);

namespace DatabaseTest\Query;

use Autowired\DependencyContainer;
use Autowired\Exception\InterfaceArgumentException;
use Configuration\ConfigurationHandler;
use Database\Adapters\Reader\PdoReaderAdapter;
use Database\Attributes\Table\Exception\MissingPrimaryKeyException;
use Database\Autowired\AutowiredHandler;
use DatabaseTest\Example\ExampleService;
use DatabaseTest\Example\InvalidEntity;
use DatabaseTest\Example\UserEntity;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Utils\ListCollection;

class BaseRepositoryTest extends TestCase
{
    private DependencyContainer $container;

    private PdoReaderAdapter $pdoReader;

    protected function setUp(): void
    {
        $this->container = DependencyContainer::getInstance();
        $this->container->addInterfaceHandler($this->container->get(AutowiredHandler::class));
        $this->container->addCustomHandler($this->container->get(ConfigurationHandler::class));
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
        $this->container->flush();
        parent::tearDown();
    }


    /**
     * @test
     */
    public function findUserByCustomValueSearch(): void
    {
        /** @var ExampleService $svc */
        $svc = $this->container->get(ExampleService::class);

        $entity = $svc->findUser('test');

        $this->assertInstanceOf(UserEntity::class, $entity);
    }

    /**
     * @test
     */
    public function findUsersByCustomValueSearch(): void
    {
        /** @var ExampleService $svc */
        $svc = $this->container->get(ExampleService::class);

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
        /** @var ExampleService $svc */
        $svc = $this->container->get(ExampleService::class);

        $collection = new ListCollection();
        $collection
            ->add(new UserEntity(3, 'SomeOther', 'other@other.de'));
        $this->assertEquals($collection, $svc->findBySomeCustomSearch());
    }

    /**
     * @test
     */
    public function getUserByPagination(): void
    {
        /** @var ExampleService $svc */
        $svc = $this->container->get(ExampleService::class);

        $collection = new ListCollection();
        $collection
            ->add(new UserEntity(2, 'test2', 'test2@test.de'));
        $this->assertEquals($collection, $svc->findByPagination());
    }

    /**
     * @throws InterfaceArgumentException
     * @throws ReflectionException
     *
     * @test
     */
    public function insertAndDeleteUser(): void
    {
        /** @var ExampleService $svc */
        $svc = $this->container->get(ExampleService::class);

        $userToCreate = new UserEntity(name: 'user persisted', email: 'persisted@test.de');

        $user = $svc->persistsUser($userToCreate);

        $this->assertNotNull($user->getUserId());
        $this->assertNotEquals($user->getUserId(), $userToCreate->getUserId());

        $this->assertNotNull($svc->findUser('user persisted'));
        $svc->deleteUser($user);
        $this->assertEquals(new UserEntity(), $svc->findUser('user persisted'));
    }

    /**
     * @throws InterfaceArgumentException
     * @throws ReflectionException
     *
     * @test
     */
    public function deleteUserInvalidEntity(): void
    {
        $this->expectException(MissingPrimaryKeyException::class);
        $this->expectErrorMessage('Entity "DatabaseTest\Example\InvalidEntity" has no defined primary key attribute');
        $this->expectExceptionCode(1);

        /** @var ExampleService $svc */
        $svc = $this->container->get(ExampleService::class);

        $userToCreate = new InvalidEntity();

        $svc->deleteUser($userToCreate);
    }

    private function getPdoReader(): PdoReaderAdapter
    {
        if (empty($this->pdoReader)) {
            $this->pdoReader = DependencyContainer::getInstance()->get(PdoReaderAdapter::class);
        }
        return $this->pdoReader;
    }
}
