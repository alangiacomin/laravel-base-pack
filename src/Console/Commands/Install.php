<?php

namespace Alangiacomin\LaravelBasePack\Console\Commands;

use Alangiacomin\LaravelBasePack\Console\Commands\Core\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Symfony\Component\Process\Process;

class Install extends Command implements PromptsForMissingInput
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
    protected $description = 'Package installation';

    /**
     * Execute the console command.
     */
    public function handleCommand(): void
    {
        $this->call('migrate:fresh');

        $this->permissions();
        $this->refactorBackend();
        $this->refactorFrontend();

        $this->call('vendor:publish', ['--tag' => 'basepack']);
        $this->call('migrate');
        $this->call('db:seed');

        $this->finish();

        $this->newLine();
    }

    private function permissions(): void
    {
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\RouteAttributes\RouteAttributesServiceProvider',
            '--tag' => 'config',
        ]);
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\Permission\PermissionServiceProvider',
        ]);
    }

    private function refactorBackend(): void
    {
        $this->newLine();
        $this->comment('refactorOriginals');

        $this->deleteFile(app_path('Http/Controllers/Controller.php'));
        $this->deleteFile(app_path('Models/User.php'));
        $this->deleteFile(base_path('routes/web.php'));
        $this->deleteFile(database_path('seeders/DatabaseSeeder.php'));
        $this->deleteDir(database_path('factories'), true);

        $this->replaceInFile(
            base_path('composer.json'),
            ['^'],
            ['']
        );

        $this->replaceInFile(
            base_path('composer.json'),
            ['\"php artisan pail --timeout=0\"'],
            [''],
        );

        $this->replaceInFile(
            base_path('composer.json'),
            ['"php": "^8.2"', '"php": "8.2"', '"php": "8.3"'],
            ['"php": "^8.3"', '"php": "^8.3"', '"php": "^8.3"'],
        );

        if (!str_contains(file_get_contents(config_path('route-attributes.php')), "app_path('Http/Controllers') => ['middleware' => ['web']]")) {
            $this->replaceInFile(
                config_path('route-attributes.php'),
                ["app_path('Http/Controllers'),"],
                ["app_path('Http/Controllers') => ['middleware' => ['web']]"],
            );
        }

        if (!str_contains(file_get_contents(config_path('auth.php')), 'App\Models\User\User::class')) {
            $this->replaceInFile(
                config_path('auth.php'),
                ['App\Models\User::class'],
                ['App\Models\User\User::class'],
            );
        }

        file_put_contents(
            base_path('phpunit.xml'),
            str_replace([
                '<!-- <env name="DB_CONNECTION" value="sqlite"/> -->',
                '<!-- <env name="DB_DATABASE" value=":memory:"/> -->',
            ],
                [
                    '<env name="DB_CONNECTION" value="sqlite"/>',
                    '<env name="DB_DATABASE" value=":memory:"/>',
                ],
                file_get_contents(base_path('phpunit.xml'))
            ));
    }

    private function refactorFrontend(): void
    {
        $this->comment('npm install');
        $this->newLine();

        $process = Process::fromShellCommandline('npm install --save-dev'.
            ' @eslint/compat@1.2.2'.
            ' @eslint/eslintrc@3.1.0'.
            ' @eslint/js@9.13.0'.
            ' axios@1.7.7'.
            ' eslint@9.13.0'.
            ' eslint-plugin-react@7.37.2'.
            ' eslint-plugin-react-hooks@5.0.0'.
            ' sass@1.77.6'.
            ' vite@5.4.10'
        );
        $process->run();
        echo $process->getOutput();

        $process = Process::fromShellCommandline('npm install --save'.
            ' @popperjs/core@2.11.8'.
            ' @vitejs/plugin-react@4.3.3'.
            ' bootstrap@5.3.3'.
            ' classnames@2.5.1'.
            ' prop-types@15.8.1'.
            ' react@18.3.1'.
            ' react-dom@18.3.1'.
            ' react-router-dom@6.28.0'
        );
        $process->run();
        echo $process->getOutput();

        $process = Process::fromShellCommandline('npm uninstall'.
            ' autoprefixer'.
            ' postcss'.
            ' tailwindcss'
        );
        $process->run();
        echo $process->getOutput();

        $this->replaceInFile(
            base_path('package.json'),
            ['^'],
            ['']
        );

        $this->comment('view');
        $this->newLine();

        $this->deleteDir(resource_path('js'), true);
        $this->deleteDir(resource_path('css'), true);

        $this->deleteFile(base_path('postcss.config.js'));
        $this->deleteFile(base_path('tailwind.config.js'));
        $this->deleteFile(base_path('vite.config.js'));
    }
}
