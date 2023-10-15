<?php

/** @noinspection PhpUnused */

namespace Alangiacomin\LaravelBasePack\Console\Commands;

use Alangiacomin\LaravelBasePack\Console\Commands\Core\Command;
use Alangiacomin\LaravelBasePack\Console\Commands\Core\StubCompiler;
use Alangiacomin\LaravelBasePack\Exceptions\BasePackException;
use Illuminate\Support\Facades\Config;

class CreateCommand extends Command
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
    protected $description = 'Create new Command, CommandHandler and CommandRule classes';

    /**
     * Execute the console command.
     *
     * @throws BasePackException
     */
    public function handleCommand(): void
    {
        $commandName = $this->argument('name');

        $this->createCommandFile($commandName);
        //        $this->createCommandHandlerFile($commandName);
        //        $this->createCommandRuleFile($commandName);
    }

    /**
     * @throws BasePackException
     */
    private function createCommandFile(string $name): void
    {
        StubCompiler::Compile(
            'Command',
            $name,
            'commands',
            [
                'commandNamespace' => "App\Commands".$this->relativeNamespace($name),
                'commandName' => $this->elementName($name),
            ]
        );

        $this->comment("$name.php created");
    }

    /**
     * @throws BasePackException
     */
    private function createCommandHandlerFile(string $name): void
    {
        StubCompiler::Compile(
            'CommandHandler',
            "{$name}Handler",
            'commands',
            [
                'handlerNamespace' => "App\Commands".$this->relativeNamespace($name),
                'commandNamespace' => "App\Commands".$this->relativeNamespace($name),
                'repositoryNamespace' => Config::get('basepack.namespaces.repositories'),
                'modelNamespace' => Config::get('basepack.namespaces.models'),
                'commandName' => $this->elementName($name),
                'aggregateName' => $aggregateName ?? '',
            ]
        );

        $this->comment("{$name}Handler.php created");
    }

    /**
     * @throws BasePackException
     */
    private function createCommandRuleFile($name): void
    {
        StubCompiler::Compile(
            'CommandRule',
            "{$name}Rule",
            'commands',
            [
                'handlerNamespace' => Config::get('basepack.namespaces.commands').$this->relativeNamespace($name),
                'commandNamespace' => Config::get('basepack.namespaces.commands').$this->relativeNamespace($name),
                'commandName' => $this->elementName($name),
            ]
        );

        $this->comment("{$name}Rule.php created");
    }
}
