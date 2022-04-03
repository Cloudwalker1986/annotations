<?php
declare(strict_types=1);

namespace DatabaseTest\Example;

use Database\Attributes\Entity\Enum;
use Database\Attributes\Table\Column;
use Database\Attributes\Table\PrimaryKey;
use Database\EntityInterface;

class UserEntity implements EntityInterface
{
    #[PrimaryKey]
    #[Column('id_user')]
    private ?int $userId;

    private ?string $name;

    private ?string $email;

    #[Enum(UserStatus::class)]
    private ?UserStatus $status;

    public function __construct(
        ?int $userId = null,
        ?string $name = null,
        ?string $email = null,
        ?UserStatus $status = null
    ) {
        $this->userId = $userId;
        $this->name = $name;
        $this->email = $email;
        $this->status = $status;
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

    public function getStatus(): UserStatus
    {
        return $this->status;
    }
}
