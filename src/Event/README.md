# Event Manager

With the `EventManager` component it is super easy to define your subscriber without handling or maintaining big files to define all subscribers/listeners

Build your subscriber and use the attribute `#[SubscribeTo]` for your method to build the link between the dispatched event and the subscribed method.

See the example below

### Example 1

```php
<?php
declare(strict_types=1);

class EventSubscriber 
{
    #[\Event\Attributes\SubscribeTo('OnSomeAction'), __CLASS__]
    public function onSomeEvent(\Event\PayloadInterface  $payload)
    {
        //do some work...
    }

}
```

There are two ways now of register the subscribers to the `EventManager`. The easiest way is to use the `Resolver` class. The `Resolver` class has one function called `resolve` and required an absolute path to the begin of the source code of the application. The function will do recursively scan all dirs and files and will register all subscribers which are tagged with the attribute of `SubscribeTo` 

See example 1

### Example 1

```php
<?php
$resolver = DependencyContainer::getInstance()->get(Resolver::class);
$resolver->resolve('absolute/path/of/application/src/code');
```

The other way would be to get the `EventManager` itself and call the function `registerSubscriber` with the required parameter. 

See example 2

### Example 2

```php
<?php
$eventManager = DependencyContainer::getInstance()->get(EventManager::class);
$resolver->registerSubscriber('EventName', new EventSubscriber(EventSubscriber::class, 'onSomeEvent'));
```

In on order to fire an event you need todo the following

### Example 1

```php
<?php
$eventManager = DependencyContainer::getInstance()->get(EventManager::class);
$resolver->dispatch('OnSomethingHappen', new SomePayload());
```
