# Configuration

With the annotation module **Configuration** you can specify any data object class as a configuration on the class level definition.
In order to assign the right value from the configuration to your configuration class you need to provide to each property the attribute ```#[Value]```

### Example

```php 
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

class PdoReader implements ReaderInterface
{
    private null|PDO $connection = null;

    #[Autowired]
    private Config $config;

    ....
}

```

### Supported configuration types


| Supported configuration types | 
|-------------------------------|
| yaml                          |  