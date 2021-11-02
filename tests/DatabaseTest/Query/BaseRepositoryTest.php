<?php
declare(strict_types=1);

namespace DatabaseTest\Query;

use DatabaseTest\Example\ExampleService;
use DatabaseTest\Example\UserEntity;
use PDO;
use PHPUnit\Framework\TestCase;
use Utils\ListCollection;

class BaseRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        $pdo = new PDO('mysql:dbname=db_test;host=127.0.0.1', 'root', 'password');
        $pdo->exec(
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
        $pdo->exec(
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
        $pdo = new PDO('mysql:dbname=db_test;host=127.0.0.1', 'root', 'password');
        $pdo->exec('DROP TABLE user');
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
}