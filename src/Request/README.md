# Routing

The annotation module **Request** provides you the possibility define the routing for the incoming request. In order to define a route for your method you need to put the attribute #[Route] to a public method.

By default, each route attributes has the request method GET assigned.

In order to register your controller and the assigned Route you need to get the instance of the DependencyContainer and call the `get` function with the FQN of the class. 

With the routing instance you can register your controllers for the dispatcher.

See the 2 below examples.

### Example 1
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

### Example 2
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

The module **Request** has one big advantage. Define for your action method already a parameter signature by using the attributes of `#[GetParameter]` or `#[PostParameter]`.

Decide by your self if you want to use each single parameter listed for your request or create a DTO class and the object will be initialized with the correct values for you.

Checkout the below two examples.

#### Example one
```php

use \Request\Attributes\Parameters\GetParameter;
use \Request\Attributes\Parameters\PostParameter;

class UserController 
{
    #[\Request\Attributes\Route('/user/login', \Request\Attributes\Route::HTTP_METHOD_POST)]
    public function login(
        #[PostParameter] $username,
        #[PostParameter(alias: 'pass')] $password
    )
    {
        
    }
    
    #[\Request\Attributes\Route('/user/\d', \Request\Attributes\Route::HTTP_METHOD_GET)]
    public function profile(#[GetParameter] int $userId)
    {
    
    }
}

```

#### Example two
```php

class UserController 
{
    #[\Request\Attributes\Route(
        '/user/login',
         \Request\Attributes\Route::HTTP_METHOD_POST
     )]
    public function login(#[\Request\Attributes\Parameters\PostParameter] LoginRequest $loginRequest)
    {
        
    }
    
    #[\Request\Attributes\Route('/user/\d', \Request\Attributes\Route::HTTP_METHOD_GET)]
    public function profile(#[\Request\Attributes\Parameters\GetParameter] int $userId)
    {
    
    }
}

class LoginRequest 
{
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
| Attribute Name   | Value Pointer                               | Alias available |
|------------------|---------------------------------------------|-----------------|
| PostParameter    | POST values                                 | Yes             |
| GetParameter     | GET values                                  | Yes             |
| RawBodyParameter | HTTP RAW Body (current supported JSON only) | Yes             |


## Request responses

The registered class for a request method entrypoint needs to return either `Response` or `RestResponse` interface. 
`RestResponse` Interface should be used in a context of an API endpoint and a `Response` Interface should be used to return HTML
