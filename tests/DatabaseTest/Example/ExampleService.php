<?php
declare(strict_types=1);

namespace DatabaseTest\Example;

use Autowired\Autowired;
use Database\AutowiredHandler;
use Database\EntityInterface;
use Database\Parameters\Pagination;
use Database\Parameters\LikeSearch;
use Utils\Collection;

class ExampleService
{
    use AutowiredHandler;

    #[Autowired]
    private UserRepository $userRepository;

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
}
