# Json

One of the smallest component is the json `Resolver` and the attribute `JsonSerializable`

With that component you have the possibility to parse your json and map it to your expected PHP object. 

Check the examples bellow.

### Example 1

```json
{
  "fieldOne": "Hello World",
  "fieldTwo": 1
}
```
```php
<?php
declare(strict_types=1);

#[\Json\Attribute\JsonSerializable]
class Object
{
    private string $fieldOne;
    
    #[\Json\Attribute\JsonField(alias: 'fieldTwo')]
    private int $fieldTwoThree;
}

class SomeService 
{
    #[Autowired\Autowired]
    private \Json\Resolver $resolver;

    public function doSomething()
    {
        $response = $this->apiClient->call();
        $this->resolver->fromJson(json_decode($response), Object::class);
    }
}
```
### Example 2

```json
{
  "fieldOne": {
      "fieldOne": "Hello World",
      "fieldTwo": 1
  }
}
```
```php
<?php
declare(strict_types=1);

#[\Json\Attribute\JsonSerializable]
class ObjectA
{
    private PhpObjectB $fieldOne;
}

#[\Json\Attribute\JsonSerializable]
class ObjectB
{
    private string $fieldOne;
    
    #[\Json\Attribute\JsonField(alias: $fieldTwo)]
    private int $fieldTwoThree;
}

class SomeService 
{
    #[Autowired\Autowired]
    private \Json\Resolver $resolver;

    public function doSomething()
    {
        $response = $this->apiClient->call();
        $this->resolver->fromJson(json_decode($response), ObjectA::class);
    }
}
```
It is important that the class which is corresponding to the current json level needs to be tagged with the attribute `#[JsonSerializable]` otherwise the exception `NotJsonSerializableException` is thrown.

### Existing attributes
| Attribute        | Description                                                                        |
|------------------|------------------------------------------------------------------------------------|
| JsonSerializable | Enables the class to be used to map values from json to php classes and vica versa |
| JsonField        | Allows to use a different property name of the php class against json key          | 
| CollectionType   | Defines which class type should be used for the collection values                  |

