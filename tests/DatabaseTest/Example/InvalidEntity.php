<?php
declare(strict_types=1);

namespace DatabaseTest\Example;

use Database\Attributes\Table\Column;
use Database\Attributes\Table\PrimaryKey;
use Database\EntityInterface;

class InvalidEntity implements EntityInterface
{
    #[Column('id_user')]
    private ?int $userId;

    private ?string $name;

    private ?string $email;

    public function __construct(?int $userId = null, ?string $name = null, ?string $email = null)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->email = $email;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

}