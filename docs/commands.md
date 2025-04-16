# Commands

## Create command

Commands represent executable actions, handled by separate handler classes.

You can generate a new command using the following Artisan command:

```bash
php artisan basepack:command {name}
```

This will create a new command class and its corresponding handler within the `App\Commands` namespace.

If the `{name}` argument includes subpaths (e.g. `User/CreateUser`), these will be reflected in the namespace structure:

```bash
php artisan basepack:command User\CreateUser
```

This will generate:

- `App\Commands\User\CreateUser.php` (the command)
- `App\Commands\User\CreateUserHandler.php` (the handler)

Each command class encapsulates the data needed to perform an action, while the handler contains the business logic to
process the command.

## Executing Commands

You can execute a command synchronously from a controller using the `executeCommand` method:

```php
class UserController extends Controller
{
    #[Get('create')]
    public function create()
    {
        $this->executeCommand(new CreateUser($data));
    }
}
```

This approach allows you to keep your controllers thin and delegate logic to dedicated handlers.

Commands can also be executed from event handlers, but they will be sent asynchronously over the message bus:

```php
class EventHappenedHandler extends EventHandler
{
    public EventHappened $event;

    public function execute(): void
    {
        $this->send(new CreateUser($data));
    }
}
```

## Dependency Injection for Properties

You can inject dependencies into your command handler classes by defining a constructor that accepts public-typed
properties. These will automatically be available within the class using
`$this->propertyName`.

For example:

```php
namespace App\Commands\User;

use AlanGiacomin\LaravelBasePack\Controllers\Controller;
use App\Models\User\UserRepository;

class CreateUserHandler extends Controller
{
    public function __construct(
        public UserRepository $userRepository
    ) {}

    public function handle(CreateUser $command)
    {
        $this->userRepository->create($command->data);
    }
}
```

In this example, the `UserRepository` is injected into the `CreateUserHandler` class, and it can be accessed directly
using `$this->userRepository`.
