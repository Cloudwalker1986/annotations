<?php
declare(strict_types=1);

namespace ConfigurationTest;

use Configuration\Attribute\Configuration;
use Configuration\Attribute\Value;

#[Configuration]
class ConfigExample
{
    #[Value('dataSource.reader.database')]
    private string $database;

    #[Value('dataSource.reader.user')]
    private string $user;

    #[Value('dataSource.reader.password')]
    private string $password;

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
