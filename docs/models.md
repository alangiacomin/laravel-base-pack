# Models, Factories, and Repositories

## Creating a Model with Factory and Repository

Models, along with their factories and repositories, are essential components of a Laravel application. Models represent
the application’s data, factories provide ways to create testable instances of models, and repositories encapsulate
database operations, promoting clean and reusable code.

You can keep your code maintainable and consistent using the following command to generate these components.

### Generating a Model with Factory and Repository

You can generate a new model, along with its factory and repository, using the following Artisan command:

```bash
php artisan basepack:model {name}
```

This will create:

- The model class under the `App\Models` namespace.
- A corresponding factory class for the model.
- A repository class encapsulating database operations for the model, along with its interface.

If the `{name}` parameter includes subpaths (e.g., `Todo\Task`), the namespace structure will reflect this:

```bash
php artisan basepack:model Todo\Task
```

This will generate:

- `App\Models\Todo\Task.php`
- `App\Models\Todo\TaskFactory.php`
- `App\Models\Todo\TaskRepository.php`
- `App\Models\Todo\Contracts\ITaskRepository.php`

The model will include common traits such as `HasFactory` and `Notifiable` and will be ready to use with Eloquent.

Example of a generated model:

```php
namespace App\Models\Todo;

use AlanGiacomin\LaravelBasePack\Models\Contracts\IModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Task implements IModel
{
    use HasFactory, Notifiable;

    protected $fillable = [
        // Define the model's fillable attributes here
    ];

    protected $hidden = [
        // Define the attributes to hide in serialization
    ];

    protected function casts(): array
    {
        return [
            // Define attributes type casting here
        ];
    }
}
```

The associated factory is created automatically for generating mock data for testing or seeding:

```php
namespace App\Models\Todo;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            // Define model mock data for testing
            // 'title' => fake()->sentence(),
            // 'completed' => fake()->boolean(),
        ];
    }
}
```

The generated repository encapsulates database queries:

```php
namespace App\Models\Todo;

use AlanGiacomin\LaravelBasePack\Repositories\Repository;
use App\Models\Todo\Contracts\ITaskRepository;
use Illuminate\Database\Eloquent\Collection;

final class TaskRepository extends Repository implements ITaskRepository
{
    public function create(array $props): Task
    {
        return Task::create($props);
    }

    public function findById(int $id): ?Task
    {
        return Task::find($id);
    }

    public function getAll(): Collection
    {
        return Task::all();
    }
}
```

The corresponding repository interface:

```php
namespace App\Models\Todo\Contracts;

use AlanGiacomin\LaravelBasePack\Repositories\IRepository;
use App\Models\Todo\Task;
use Illuminate\Database\Eloquent\Collection;

interface ITaskRepository extends IRepository
{
    public function getAll(): Collection;
}
```

---

### Workflow Overview

By leveraging the `basepack:model` command and the generated components:

- **Models**: Represent the application’s data and contain attributes, relationships, and logic associated with an
  individual record.
- **Factories**: Help generate mock data for testing and seeding.
- **Repositories**: Encapsulate database queries to improve maintainability and logic reuse.

This structure promotes clean separation of concerns and improves the overall testability and maintainability of your
application.

```bash
# Example workflow:
# Create a model, factory, and repository:
php artisan basepack:model Todo\Task
```
