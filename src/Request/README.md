# Routing

With the annotation module **Request** you can define a route for given HTTP request. In order to define a route you need to put the attribute #[Route] to a public method.



####Example 1:
```php

class UserController 
{
    #[\Request\Attributes\Route('/user/login', \Request\Attributes\Route::HTTP_METHOD_POST)]
    public function login()
    {
    
    }
    
    #[\Request\Attributes\Route('/user/\d', \Request\Attributes\Route::HTTP_METHOD_GET)]
    public function profile()
    {
    
    }
}

$container = \Autowired\DependencyContainer::getInstance();

/** @var \Request\Routing $routing */
$routing = $container->get(\Request\Routing::class);
$routing->registerController(UserController::class);

$response = $routing->dispatchRoute($_SERVER['REQUEST_URI']);
```

####Example 2:
```php

class UserController 
{
    #[\Request\Attributes\Route('/user/login', \Request\Attributes\Route::HTTP_METHOD_POST)]
    public function login()
    {
    
    }
    
    #[\Request\Attributes\Route('/user/\d', \Request\Attributes\Route::HTTP_METHOD_GET)]
    public function profile()
    {
    
    }
}

$container = \Autowired\DependencyContainer::getInstance();

/** @var \Request\Routing $routing */
$routing = $container->get(\Request\Routing::class);
$routing->registerController(UserController::class);

$dispatcher = $routing->createDispatcher($_SERVER['REQUEST_URI']);

$response = $dispatcher->dispatch();
```

### Route parameters
| Parameter | type   | Required | Default                | 
|-----------|--------|----------|------------------------|
| path      | string | yes      | -                      |
| method    | string | no       | Route::HTTP_METHOD_GET |

## Request Parameter

One advantage of the module **Request** within the registration of method the for the request you can define parameters for the method which will be parsed either by the GET|POST parameters, by the request URI or by the raw body content.

#### Example one
```php

class UserController 
{
    #[\Request\Attributes\Route('/user/login', \Request\Attributes\Route::HTTP_METHOD_POST)]
    public function login(
    #[\Request\Attributes\Parameters\PostParameter] $username,
    #[\Request\Attributes\Parameters\PostParameter(alias: 'pass')] $password
    )
    {
        
    }
    
    #[\Request\Attributes\Route('/user/\d', \Request\Attributes\Route::HTTP_METHOD_GET)]
    public function profile(#[\Request\Attributes\Parameters\GetParameter] int $userId)
    {
    
    }
}

```

#### Example two
```php

class UserController 
{
    #[\Request\Attributes\Route(
    '/user/login', \Request\Attributes\Route::HTTP_METHOD_POST)]
    public function login(
        #[\Request\Attributes\Parameters\PostParameter] LoginRequest $loginRequest
    )
    {
        
    }
    
    #[\Request\Attributes\Route('/user/\d', \Request\Attributes\Route::HTTP_METHOD_GET)]
    public function profile(
        #[\Request\Attributes\Parameters\GetParameter] int $userId)
    {
    
    }
}

class LoginRequest 
{
    #[PostParameter]
    private string $username;
    
    #[PostParameter(alias: 'pass')]
    private string $password;
    
    public function getUsername(): string
    {
        return $this->username;
    }
    
    public function getPassword(): string
    {
        return $this->password;
    }
}
```
### Possible Attributes for parameters
| Attribute Name   | Value Pointer | Alias available |
|------------------|---------------|-----------------|
| PostParameter    | PostParameter | Yes             |
| GetParameter     | GetParameter  | Yes             |
| RawBodyParameter | JsonRequest   | Yes             |


## Request responses

The registered class for a request method entrypoint needs to return either Response or RestResponse interface. 
RestResponse Interface should be used in a context of an API endpoint and a Response Interface should be used to return HTML