<?php

namespace Alangiacomin\LaravelBasePack\Console\Commands;

use Alangiacomin\LaravelBasePack\Console\Commands\Core\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

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
        $this->refactorOriginals();

        $this->newLine();
    }

    private function refactorOriginals(): void
    {
        $this->newLine();
        $this->comment('refactorOriginals');

        $this->deleteFile(app_path('Http/Controllers/Controller.php'));

        $this->moveFile(
            app_path('Models/User.php'),
            app_path('Models/User/User.php'),
            ['namespace App\Models;'],
            ['namespace App\Models\User;']
        );

        $this->moveFile(
            database_path('factories/UserFactory.php'),
            app_path('Models/User/UserFactory.php'),
            ['namespace Database\Factories;'],
            ['namespace App\Models\User;']
        );
        if (!str_contains(file_get_contents(app_path('Models/User/UserFactory.php')), '$model = User::class')) {
            $this->replaceInFile(
                app_path('Models/User/UserFactory.php'),
                ['protected static ?string $password;'],
                ['protected static ?string $password;'.PHP_EOL.PHP_EOL.'    protected $model = User::class;'],
            );
        }

        if (!str_contains(file_get_contents(database_path('seeders/DatabaseSeeder.php')), '(new UserFactory())')) {
            $this->replaceInFile(
                database_path('seeders/DatabaseSeeder.php'),
                ['User::factory()'],
                ['(new \App\Models\User\UserFactory())'],
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
}
