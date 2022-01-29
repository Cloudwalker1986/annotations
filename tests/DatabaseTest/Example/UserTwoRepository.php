<?php
declare(strict_types=1);

namespace DatabaseTest\Example;

use Database\CrudRepository;
use Database\CrudRepositoryInterface;
use Database\Parameters\Pagination;
use Utils\Collection;
use Database\Attributes\Query;
use Database\Parameters\LikeSearch;
use Database\Attributes\Repository;

#[Repository('user', UserEntity::class)]
interface UserTwoRepository extends CrudRepositoryInterface
{
    #[Query('WHERE `name` = :name')]
    public function findMyUserByName(string $name): ?UserEntity;

    #[Query("WHERE (`name` LIKE :name OR `email` LIKE :email)")]
    public function findAllUsersBySearch(LikeSearch $search): Collection;

    #[Query('WHERE (`name` = :name OR `email` = :search)')]
    public function someCrazyTestSearch(string $name, LikeSearch $search): Collection;

    #[Query('')]
    public function findByPagination(Pagination $pagination): Collection;
}
