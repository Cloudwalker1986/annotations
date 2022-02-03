# Configuration

The annotation module **Database** provides you the possibility to define your database interface and the module takes care of fetching your data. A concrete method implementation is not required|needed.
Define on the class level with the attribute ```#[Repository]``` that these class belongs to a database table and which entity object belongs to it.
Each repository method 
- needs to have a ```#[Query]``` attribute defined with a given SQL statement started after SELECT * FROM `tableName`
- is returning either a Database/Entity interface or a Collection of Database/Entity interfaces 

### Repository parameters
| Parameter | type   | Required | Description                                                   | 
|-----------|--------|----------|---------------------------------------------------------------|
| table     | string | yes      | The name of the table to which this repository will be linked |
| entity    | string | yes      | The fully qualified class name                                |

### Example

```php 
#[Repository('user', UserEntity::class)]
interface UserRepository
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
```

## Additional attribute

The database module implemented a  ```#[Column]``` attribute where you can define of which php class property is mapped to which table column. By default, the property name will be used to identify the column of the table 

### Entity attributes
| Attribute   | type   | Required | Description                          | 
|-------------|--------|----------|--------------------------------------|
| PrimaryKey  | -      | yes      | Define the primary key of the entity |
| column      | string | yes      | The name of the table column         |

