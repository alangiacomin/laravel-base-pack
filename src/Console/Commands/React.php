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
            ' react@18.3.1'.
            ' react-dom@18.3.1'
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

        if (!str_contains(file_get_contents(base_path('vite.config.js')), 'resources/js/index.jsx')) {
            $this->replaceInFile(
                base_path('vite.config.js'),
                ['resources/js/app.js'],
                ['resources/js/index.jsx']
            );
        }
        if (!str_contains(file_get_contents(base_path('vite.config.js')), 'resources/css/app.scss')) {
            $this->replaceInFile(
                base_path('vite.config.js'),
                ['resources/css/app.css'],
                ['resources/css/app.scss']
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
        $this->deleteFile(resource_path('css/app.css'));
        $this->deleteFile(base_path('postcss.config.js'));
        $this->deleteFile(base_path('tailwind.config.js'));

        $this->copyFile(
            __DIR__.'/Stubs/react/index.jsx.stub',
            resource_path('js/index.jsx')
        );
        $this->copyFile(
            __DIR__.'/Stubs/react/App.jsx.stub',
            resource_path('js/App.jsx')
        );
        $this->copyFile(
            __DIR__.'/Stubs/react/Button.jsx.stub',
            resource_path('js/Button.jsx')
        );

        $this->copyFile(
            __DIR__.'/Stubs/react/app.scss.stub',
            resource_path('css/app.scss')
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

        if (!str_contains(file_get_contents(base_path('routes/web.php')), 'view(\'home\')')) {
            $this->replaceInFile(
                base_path('routes/web.php'),
                ['view(\'welcome\')'],
                ['view(\'home\')']
            );
        }
    }
}
