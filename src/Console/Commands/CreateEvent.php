<?php

/** @noinspection PhpUnused */

namespace Alangiacomin\LaravelBasePack\Console\Commands;

use Alangiacomin\LaravelBasePack\Console\Commands\Core\Command;
use Alangiacomin\LaravelBasePack\Console\Commands\Core\StubCompiler;
use Alangiacomin\LaravelBasePack\Core\NamespaceUtility;
use Alangiacomin\LaravelBasePack\Exceptions\BasePackException;

class CreateEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'basepack:event {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Event and EventHandler classes';

    /**
     * Base namespace for events
     */
    private string $baseNamespace = "App\Events";

    /**
     * Execute the console command.
     *
     * @throws BasePackException
     */
    public function handleCommand(): void
    {
        $commandName = $this->argument('name');

        $this->createEventFile($commandName);
        $this->createEventHandlerFile($commandName);
    }

    /**
     * @throws BasePackException
     */
    private function createEventFile($name): void
    {
        $errors = StubCompiler::Compile(
            'Event',
            $name,
            $this->baseNamespace,
            [
                'eventNamespace' => $this->baseNamespace.NamespaceUtility::relativeNamespace($name),
                'eventName' => NamespaceUtility::elementName($name),
            ]
        );

        $this->printResult("$name.php created", $errors);
    }

    /**
     * @throws BasePackException
     */
    private function createEventHandlerFile($name): void
    {
        $errors = StubCompiler::Compile(
            'EventHandler',
            "{$name}Handler",
            $this->baseNamespace,
            [
                'handlerNamespace' => $this->baseNamespace.NamespaceUtility::relativeNamespace($name),
                'eventName' => NamespaceUtility::elementName($name),
            ]

        );

        $this->printResult("{$name}Handler.php created", $errors);
    }
}
