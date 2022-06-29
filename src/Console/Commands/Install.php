<?php

namespace Alangiacomin\LaravelBasePack\Console\Commands;

use Alangiacomin\LaravelBasePack\Console\Commands\Core\Command;
use Exception;

/**
 * @method call(string $string)
 */
class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'basepack:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Laravel Base Pack';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function handleCommand(): void
    {
        $this->publish();
        $this->model();
        $this->database();
        $this->policies();
        $this->controller();
        $this->middleware();
        $this->routes();
        $this->optimize();

        $this->newLine();
    }

    private function publish()
    {
        $this->newLine();
        $this->comment("Publish vendor files");
        $this->call("vendor:publish", [
            "--provider" => "Spatie\Permission\PermissionServiceProvider",
        ]);
        $this->call("vendor:publish", [
            "--provider" => "Alangiacomin\LaravelBasePack\LaravelBasePackServiceProvider",
            "--tag" => "basepack-install",
        ]);
        $this->call("vendor:publish", [
            "--tag" => "basepack-admin",
        ]);
    }

    private function database()
    {
        $this->newLine();
        $this->comment("Create jobs table");

        $migration = "create_jobs_table";
        $files = glob(database_path('migrations/*create_jobs_table*.php'));
        if (count($files) > 0)
        {
            $this->info("Already exists");
        }
        else
        {
            $this->call("queue:table");
        }

        $this->newLine();
        $this->comment("Migrating database");

        $this->call("migrate:fresh");
        $this->call("db:seed", [
            "--class" => "AdminSeeder",
        ]);
    }

    private function model()
    {
        $this->newLine();
        $this->comment("Add trait to User model");
        $useNamespace = "use Spatie\Permission\Traits\HasRoles;";
        $useNamespace .= PHP_EOL;
        $useNamespace .= "use Alangiacomin\LaravelBasePack\Traits\HasExtendedPermissions;";
        $useTrait = "use HasRoles, HasExtendedPermissions;";
        $userPath = app_path('Models/User.php');
        if (file_exists($userPath))
        {
            $str = file_get_contents($userPath);
            if ($str !== false && !str_contains($str, $useNamespace))
            {
                $str = str_replace(
                    "as Authenticatable;",
                    "as Authenticatable;".PHP_EOL.$useNamespace,
                    $str
                );
                file_put_contents($userPath, $str);

            }
            if ($str !== false && !str_contains($str, $useTrait))
            {
                $sep1 = "class User";
                $explode1 = explode($sep1, $str, 2);
                if (count($explode1) > 1)
                {
                    $sep2 = "{";
                    $explode2 = explode($sep2, $explode1[1], 2);
                    if (count($explode2) > 1)
                    {
                        $str = implode([
                            $explode1[0],
                            $sep1,
                            $explode2[0],
                            $sep2,
                            PHP_EOL."    ".$useTrait,
                            $explode2[1],
                        ]);
                        file_put_contents($userPath, $str);
                    }
                }
            }
            if (!str_contains($str, $useTrait))
            {
                $this->warn("Failed");
            }
        }
        else
        {
            $this->warn("User model file not found");
        }
    }

    private function policies()
    {
        $this->newLine();
        $this->comment("Policies");
        $policyPath = app_path('Policies/UserPolicy.php');
        // unlink($policyPath);
        if (!file_exists($policyPath))
        {
            $this->call("make:policy", [
                "name" => "UserPolicy",
                "--model" => 'User',
            ]);
        }
        $str = file_get_contents($policyPath);

        $permissions = [
            'viewAny' => 'viewAny',
            'create' => 'create',
            'view' => 'view',
            'update' => 'update',
            'delete' => 'delete',
            'restore' => 'restore',
            'forceDelete' => 'forceDelete',
        ];
        foreach ($permissions as $method => $perm)
        {
            $str = preg_replace(
                "/(function {$method}\([A-Za-z0-9$, ]+\)[^{]+{[\r\n ]+)\/\/([\r\n ]+})/",
                "\${1}return \$user->hasPermissionTo('{$perm}');\${2}",
                $str);
        }
        file_put_contents($policyPath, $str);

        $authServiceProviderPath = app_path('Providers/AuthServiceProvider.php');
        if (file_exists($authServiceProviderPath))
        {
            $str = file_get_contents($authServiceProviderPath);
            if (!str_contains($str, "Implicitly grant 'Admin' role all permissions"))
            {
                $gateAdmin = "
        
        // DON'T REMOVE THIS COMMENT
        // Implicitly grant 'Admin' role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function (\$user, \$ability) {
            return \$user->hasRole('admin') ? true : null;
        });";
                $registerPolicies = '$this->registerPolicies();';

                $str = str_replace($registerPolicies, $registerPolicies.$gateAdmin, $str);
                file_put_contents($authServiceProviderPath, $str);
            }
        }
    }

    private function controller()
    {
        $this->newLine();
        $this->comment("Remove Laravel Controller");
        $controllerFile = app_path('Http/Controllers/Controller.php');
        if (file_exists($controllerFile))
        {
            unlink($controllerFile);
        }
    }

    private function middleware()
    {
        $this->newLine();
        $this->comment("Middleware");
        $filePath = app_path('Http/Kernel.php');
        $str = file_get_contents($filePath);
        if (!str_contains($str, '\App\Http\Middleware\TranslationManager::class,'))
        {
            $str = preg_replace(
                '/\'web\' => \[/',
                "'web' => [".PHP_EOL."            \App\Http\Middleware\TranslationManager::class,",
                $str);
            file_put_contents($filePath, $str);
        }

        $filePath = app_path('Http/Kernel.php');
        $str = file_get_contents($filePath);
        if (!str_contains($str, '\Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class'))
        {
            $str = preg_replace(
                '/protected \$routeMiddleware = \[/',
                "protected \$routeMiddleware = [".PHP_EOL."        'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,",
                $str);
        }
        if (!str_contains($str, '\Spatie\Permission\Middlewares\PermissionMiddleware::class'))
        {
            $str = preg_replace(
                '/protected \$routeMiddleware = \[/',
                "protected \$routeMiddleware = [".PHP_EOL."        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,",
                $str);
        }
        if (!str_contains($str, '\Spatie\Permission\Middlewares\RoleMiddleware::class'))
        {
            $str = preg_replace(
                '/protected \$routeMiddleware = \[/',
                "protected \$routeMiddleware = [".PHP_EOL."        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,",
                $str);
        }
        file_put_contents($filePath, $str);
    }

    private function routes()
    {
        $this->newLine();
        $this->comment("Routes");
        $filePath = base_path('routes/Web.php');
        $webStubPath = __DIR__.'./stubs/php/Web.php.stub';
        $str = file_get_contents($filePath);
        $str = preg_replace(
            '/^Route::get\(\'\/\', function \(\) {[^}]*}\);$/m',
            file_get_contents($webStubPath),
            $str);
        file_put_contents($filePath, $str);

        $filePath = app_path('Http/Middleware/VerifyCsrfToken.php');
        $str = file_get_contents($filePath);
        if (!str_contains($str, 'auth/logout'))
        {
            $str = preg_replace(
                '/protected \$except = \[/',
                "protected \$except = [".PHP_EOL."        'auth/logout',",
                $str);
            file_put_contents($filePath, $str);
        }

        $filePath = base_path('resources/views/welcome.blade.php');
        $str = file_get_contents($filePath);
        if (!str_contains($str, 'csrf_token()'))
        {
            $str = preg_replace(
                '/<head>/',
                '<head><meta name="csrf-token" content="{{ csrf_token() }}">',
                $str);
            file_put_contents($filePath, $str);
        }
    }

    private function optimize()
    {
        $this->newLine();
        $this->comment("Clear optimization");
        $this->call("optimize:clear");
    }
}
