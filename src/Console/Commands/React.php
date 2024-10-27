<?php

namespace Alangiacomin\LaravelBasePack\Console\Commands;

use Alangiacomin\LaravelBasePack\Console\Commands\Core\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Symfony\Component\Process\Process;

class React extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'basepack:react';

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
        $this->npm();
        $this->viteconfig();
        $this->view();

        $this->newLine();
    }

    public function npm(): void
    {
        $this->comment('npm');
        $this->newLine();

        $process = Process::fromShellCommandline('npm install --save-dev @eslint/compat @eslint/eslintrc @eslint/js eslint eslint-plugin-react eslint-plugin-react-hooks');
        $process->run();
        echo $process->getOutput();

        $process = Process::fromShellCommandline('npm install --save @vitejs/plugin-react react react-dom');
        $process->run();
        echo $process->getOutput();
    }

    public function viteconfig(): void
    {
        $this->comment('viteconfig');
        $this->newLine();

        if (!str_contains(file_get_contents(base_path('vite.config.js')), '\'@vitejs/plugin-react\'')) {
            $this->replaceInFile(
                base_path('vite.config.js'),
                ['import laravel from \'laravel-vite-plugin\';'],
                ['import laravel from \'laravel-vite-plugin\';'.PHP_EOL.'import react from \'@vitejs/plugin-react\';']
            );
        }

        if (!str_contains(file_get_contents(base_path('vite.config.js')), 'resources/js/app.jsx')) {
            $this->replaceInFile(
                base_path('vite.config.js'),
                ['resources/js/app.js'],
                ['resources/js/app.jsx']
            );
        }

        if (!str_contains(file_get_contents(base_path('vite.config.js')), 'react(),')) {
            $this->replaceInFile(
                base_path('vite.config.js'),
                ['        laravel({'],
                ['        react(),'.PHP_EOL.'        laravel({']
            );
        }
    }

    public function view(): void
    {
        $this->comment('view');
        $this->newLine();

        $this->deleteFile(resource_path('js/app.js'));

        $this->copyFile(
            __DIR__.'/Stubs/react/app.jsx.stub',
            resource_path('js/app.jsx')
        );

        $this->copyFile(
            __DIR__.'/Stubs/php/UserController.php.stub',
            app_path('Http/Controllers/UserController.php')
        );

        $this->copyFile(
            __DIR__.'/Stubs/php/IUserRepository.php.stub',
            app_path('Repositories/IUserRepository.php')
        );
        $this->copyFile(
            __DIR__.'/Stubs/php/UserRepository.php.stub',
            app_path('Repositories/UserRepository.php')
        );

        $this->copyFile(
            __DIR__.'/Stubs/react/home.blade.php.stub',
            resource_path('views/home.blade.php')
        );

        if (!str_contains(file_get_contents(base_path('routes/web.php')), '/getUser')) {
            $this->replaceInFile(
                base_path('routes/web.php'),
                ['use Illuminate\Support\Facades\Route;'],
                ['use Illuminate\Support\Facades\Route;'.PHP_EOL.PHP_EOL.
                    'Route::get(\'/getUser\', function () { return view(\'home\'); });']
            );
        }

        if (!str_contains(file_get_contents(base_path('routes/web.php')), 'UserController')) {
            $this->replaceInFile(
                base_path('routes/web.php'),
                ['use Illuminate\Support\Facades\Route;'],
                ['use Illuminate\Support\Facades\Route;'.PHP_EOL.PHP_EOL.
                    'Route::controller(App\Http\Controllers\UserController::class)->group(function () {'.PHP_EOL.
                    '    Route::post(\'/user/login\', \'login\')->name(\'user.login\');'.PHP_EOL.
                    '    Route::post(\'/user/logout\', \'logout\')->name(\'user.logout\');'.PHP_EOL.
                    '    Route::get(\'/user/loadUser\', \'get\')->name(\'user.loadUser\');'.PHP_EOL.
                    '});']
            );
        }
    }
}
