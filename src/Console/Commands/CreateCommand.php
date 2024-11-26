<?php

namespace Alangiacomin\LaravelBasePack\Console\Commands;

use Alangiacomin\LaravelBasePack\Console\Commands\Core\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class CreateCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'basepack:command {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create command and its handler';

    protected string $relNamespace = '';

    protected string $commandName;

    /**
     * Execute the console command.
     */
    public function handleCommand(): void
    {
        $name = $this->argument('name');
        $tokens = explode('\\', $name);
        $this->commandName = array_pop($tokens);
        $relDir = implode('\\', $tokens);

        if (!empty($relDir)) {
            $this->relNamespace = '\\'.$relDir;
            mkdir(app_path('Commands'.$this->relNamespace), recursive: true);
        }

        $this->createCommand();
        $this->createHandler();
        $this->newLine();
    }

    private function createCommand(): void
    {
        $this->newLine();
        $this->comment("Command: {$this->commandName}");

        $filePath = app_path('Commands'.$this->relNamespace.'/'.$this->commandName.'.php');
        $stubPath = __DIR__.'/stubs/Commands/Stub.php.stub';
        $stubContents = file_get_contents($stubPath);
        $stubContents = preg_replace(
            '/\{commandName}/',
            $this->commandName,
            $stubContents
        );
        $stubContents = preg_replace(
            '/\{relNamespace}/',
            $this->relNamespace,
            $stubContents
        );
        file_put_contents($filePath, $stubContents);
    }

    private function createHandler(): void
    {
        $this->newLine();
        $this->comment("CommandHandler: {$this->commandName}Handler");

        $filePath = app_path('Commands'.$this->relNamespace.'/'.$this->commandName.'Handler.php');
        $stubPath = __DIR__.'/stubs/Commands/StubHandler.php.stub';
        $stubContents = file_get_contents($stubPath);
        $stubContents = preg_replace(
            '/\{commandName}/',
            $this->commandName,
            $stubContents
        );
        $stubContents = preg_replace(
            '/\{relNamespace}/',
            $this->relNamespace,
            $stubContents
        );
        file_put_contents($filePath, $stubContents);
    }
}
