<?php
declare(strict_types=1);

namespace Database\Adapters;

interface ConnectionInterface
{
    public function getPassword(): string;

    public function getUser(): string;

    public function getDatabase(): string;

    public function getHost(): string;
}
