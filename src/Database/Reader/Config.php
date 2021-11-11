<?php
declare(strict_types=1);

namespace Database\Reader;


use Configuration\Attribute\Configuration;
use Configuration\Attribute\Value;

#[Configuration]
class Config
{
    #[Value('dataSource.mysql.password')]
    private string $password;

    #[Value('dataSource.mysql.user')]
    private string $user;

    #[Value('dataSource.mysql.database')]
    private string $database;

    #[Value('dataSource.mysql.host')]
    private string $host;

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getHost(): string
    {
        return $this->host;
    }

}
