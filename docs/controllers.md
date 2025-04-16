# Controllers

## Creating controller

You can generate a new controller using the following Artisan command:

```bash
php artisan basepack:controller {name}
```

This will create a new controller within the `App\Http\Controllers` namespace.

If the `{name}` parameter includes subpaths (e.g., `User\UserController`), these will be reflected in the namespace
structure.

```bash
php artisan basepack:controller User\UserController
```

This will generate:

- `App\Http\Controllers\User\UserController.php`

### Example Action Executing a Command

An action in a controller can delegate business logic to a command. To do this, you can use the `executeCommand` method:

```php
namespace App\Http\Controllers\User;

use AlanGiacomin\LaravelBasePack\Controllers\Controller;
use App\Commands\User\CreateUser;
use Spatie\RouteAttributes\Attributes\Post;

class UserController extends Controller
{
    #[Post('create')]
    public function create()
    {
        $data = request()->all();

        // Use the evolved command to delegate logic
        $this->executeCommand(new CreateUser($data));

        return response()->json(['message' => 'User created successfully']);
    }
}
```

In this example, the logic for creating the user is encapsulated in the `CreateUser` command, which will be processed by
its corresponding handler. The controller simply acts as an interface between the client and the system.

## Dependency Injection for Properties

You can inject dependencies into your controller classes by defining a constructor that accepts public-typed
properties. These will automatically be available within the class using `$this->propertyName`.

For example:

```php
namespace App\Http\Controllers\User;

use AlanGiacomin\LaravelBasePack\Controllers\Controller;
use App\Models\User\UserRepository;

class UserController extends Controller
{
    public function __construct(
        public UserRepository $userRepository
    ) {}

    public function create()
    {
        $userRepository->create(..);
    }
}
```

In this example, the `UserRepository` is injected into the `CreateUserHandler` class, and it can be accessed directly
using `$this->userRepository`.
