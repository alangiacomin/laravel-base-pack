<?php

namespace AlanGiacomin\LaravelBasePack\Console\Commands;

use AlanGiacomin\LaravelBasePack\Console\Commands\Core\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Str;

class CreateModel extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'basepack:model {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create model';

    protected string $relNamespace = '';

    protected string $modelName;

    /**
     * Execute the console command.
     */
    public function handleCommand(): void
    {
        $name = $this->argument('name');
        $tokens = explode('\\', $name);
        $this->modelName = array_pop($tokens);
        $relDir = implode('\\', $tokens);

        if (!empty($relDir)) {
            $this->relNamespace = '\\'.$relDir;
            $this->mkdirIfMissing(app_path('Models'.$this->relNamespace));
        }

        $this->createModel();
        $this->createFactory();
        $this->createRepository();
        $this->createMigration();
        $this->newLine();
    }

    private function createModel(): void
    {
        $this->newLine();
        $this->comment("Model: $this->modelName");

        $filePath = app_path('Models'.$this->relNamespace.'/'.$this->modelName.'.php');
        $stubPath = __DIR__.'/stubs/Models/Stub.php.stub';
        $stubContents = file_get_contents($stubPath);
        $stubContents = preg_replace(
            '/\{modelName}/',
            $this->modelName,
            $stubContents
        );
        $stubContents = preg_replace(
            '/\{relNamespace}/',
            $this->relNamespace,
            $stubContents
        );
        file_put_contents($filePath, $stubContents);
    }

    private function createFactory(): void
    {
        $this->newLine();
        $this->comment("Factory: {$this->modelName}Factory");

        $filePath = app_path('Models'.$this->relNamespace.'/'.$this->modelName.'Factory.php');
        $stubPath = __DIR__.'/stubs/Models/StubFactory.php.stub';
        $stubContents = file_get_contents($stubPath);
        $stubContents = preg_replace(
            '/\{modelName}/',
            $this->modelName,
            $stubContents
        );
        $stubContents = preg_replace(
            '/\{relNamespace}/',
            $this->relNamespace,
            $stubContents
        );
        file_put_contents($filePath, $stubContents);
    }

    private function createRepository(): void
    {
        $this->newLine();
        $this->comment("Repository: {$this->modelName}Repository");

        $this->mkdirIfMissing(app_path('Models'.$this->relNamespace.'/Contracts'));
        $filePath = app_path('Models'.$this->relNamespace.'/Contracts/I'.$this->modelName.'Repository.php');
        $stubPath = __DIR__.'/stubs/Models/StubIRepository.php.stub';
        $stubContents = file_get_contents($stubPath);
        $stubContents = preg_replace(
            '/\{modelName}/',
            $this->modelName,
            $stubContents
        );
        $stubContents = preg_replace(
            '/\{relNamespace}/',
            $this->relNamespace,
            $stubContents
        );
        file_put_contents($filePath, $stubContents);

        $filePath = app_path('Models'.$this->relNamespace.'/'.$this->modelName.'Repository.php');
        $stubPath = __DIR__.'/stubs/Models/StubRepository.php.stub';
        $stubContents = file_get_contents($stubPath);
        $stubContents = preg_replace(
            '/\{modelName}/',
            $this->modelName,
            $stubContents
        );
        $stubContents = preg_replace(
            '/\{relNamespace}/',
            $this->relNamespace,
            $stubContents
        );
        file_put_contents($filePath, $stubContents);
    }

    private function createMigration(): void
    {
        $this->newLine();
        $this->comment('Migration: create_'.Str::snake($this->modelName).'s_table');

        $this->call('make:migration', ['name' => 'create_'.Str::snake($this->modelName).'s_table']);
        $this->call('migrate');
    }
}
