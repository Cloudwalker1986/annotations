<?php
declare(strict_types=1);

namespace DatabaseTest\Example;

use Autowired\Autowired;
use Database\EntityInterface;
use Database\Parameters\Pagination;
use Database\Parameters\LikeSearch;
use Utils\Collection;

class ExampleService
{
    #[Autowired]
    private UserRepository $userRepository;

    #[Autowired]
    private UserTwoRepository $userTwoRepository;

    public function findUser(string $name): ?EntityInterface
    {
        return $this->userRepository->findMyUserByName($name);
    }

    public function findAll(): Collection
    {
        $search = new LikeSearch();
        $search->add('name', 'test')->add('email', 'test');

        return $this->userRepository->findAllUsersBySearch($search);
    }

    public function findBySomeCustomSearch(): Collection
    {
        $search = new LikeSearch();
        $search->add('search','other@other.de');

        return $this->userRepository->someCrazyTestSearch('SomeOther', $search);
    }

    public function findByPagination(): Collection
    {
        $pagination = new Pagination(1,1);

        return $this->userRepository->findByPagination($pagination);
    }

    public function persistsUser(UserEntity $user): UserEntity|EntityInterface
    {
        return $this->userTwoRepository->persists($user);
    }

    public function deleteUser(InvalidEntity|UserEntity $user): bool
    {
        return $this->userTwoRepository->delete($user);
    }
}
