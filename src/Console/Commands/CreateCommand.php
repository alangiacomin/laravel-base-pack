<?php

/** @noinspection PhpUnused */

namespace Alangiacomin\LaravelBasePack\Console\Commands;

use Alangiacomin\LaravelBasePack\Console\Commands\Core\Command;
use Alangiacomin\LaravelBasePack\Console\Commands\Core\StubCompiler;
use Alangiacomin\LaravelBasePack\Core\NamespaceUtility;
use Alangiacomin\LaravelBasePack\Exceptions\BasePackException;

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
     * Base namespace for commands
     */
    private string $baseNamespace = "App\Commands";

    /**
     * Execute the console command.
     *
     * @throws BasePackException
     */
    public function handleCommand(): void
    {
        $commandName = $this->argument('name');

        $this->createCommandFile($commandName);
        $this->createCommandHandlerFile($commandName);
        $this->createCommandRuleFile($commandName);
    }

    /**
     * @throws BasePackException
     */
    private function createCommandFile(string $name): void
    {
        $errors = StubCompiler::Compile(
            'Command',
            $name,
            $this->baseNamespace,
            [
                'commandNamespace' => $this->baseNamespace.NamespaceUtility::relativeNamespace($name),
                'commandName' => NamespaceUtility::elementName($name),
            ]
        );

        $this->printResult("$name.php created", $errors);
    }

    /**
     * @throws BasePackException
     */
    private function createCommandHandlerFile(string $name): void
    {
        $errors = StubCompiler::Compile(
            'CommandHandler',
            "{$name}Handler",
            $this->baseNamespace,
            [
                'handlerNamespace' => $this->baseNamespace.NamespaceUtility::relativeNamespace($name),
                'commandName' => NamespaceUtility::elementName($name),
            ]
        );

        $this->printResult("{$name}Handler.php created", $errors);
    }

    /**
     * @throws BasePackException
     */
    private function createCommandRuleFile($name): void
    {
        $errors = StubCompiler::Compile(
            'CommandRule',
            "{$name}Rule",
            $this->baseNamespace,
            [
                'handlerNamespace' => $this->baseNamespace.NamespaceUtility::relativeNamespace($name),
                'commandName' => NamespaceUtility::elementName($name),
            ]
        );

        $this->printResult("{$name}Rule.php created", $errors);
    }
}
