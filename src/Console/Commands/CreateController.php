<?php

/** @noinspection PhpUnused */

namespace Alangiacomin\LaravelBasePack\Console\Commands;

use Alangiacomin\LaravelBasePack\Console\Commands\Core\Command;
use Alangiacomin\LaravelBasePack\Console\Commands\Core\StubCompiler;
use Alangiacomin\LaravelBasePack\Core\NamespaceUtility;
use Alangiacomin\LaravelBasePack\Exceptions\BasePackException;

class CreateController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'basepack:controller {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Controller class';

    /**
     * Base namespace for controllers
     */
    private string $baseNamespace = "App\Http\Controllers";

    /**
     * Execute the console command.
     *
     * @throws BasePackException
     */
    public function handleCommand(): void
    {
        $name = $this->argument('name');

        $this->createControllerFile($name);
    }

    /**
     * @throws BasePackException
     */
    private function createControllerFile(string $name): void
    {
        StubCompiler::Compile(
            'Controller',
            "{$name}Controller",
            $this->baseNamespace,
            [
                'namespace' => $this->baseNamespace.NamespaceUtility::relativeNamespace($name),
                'name' => NamespaceUtility::elementName($name),
            ]
        );

        $this->comment("{$name}Controller.php created");
    }
}
