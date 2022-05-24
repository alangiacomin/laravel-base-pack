<?php /** @noinspection PhpUnused */

namespace Alangiacomin\LaravelBasePack\Console\Commands;

use Alangiacomin\LaravelBasePack\Console\Commands\Core\Command;
use Alangiacomin\LaravelBasePack\Console\Commands\Core\StubCompiler;
use Exception;

class CreateLogger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'basepack:logger {--c|create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Logger service class';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function handleCommand()
    {
        $stubFile = __DIR__."\\stubs\\LoggerOpenApi.php.stub";
        $newFile = base_path()."\\config\\logger-open-api.php";

        if (!$this->option('create'))
        {
            shell_exec("composer require --dev jane-php/open-api-3");
            shell_exec("composer require jane-php/open-api-runtime");

            file_put_contents($newFile, file_get_contents($stubFile));

            $this->createLoggerFile();
        }
        else
        {
            shell_exec("php vendor/bin/jane-openapi generate --config-file=$newFile");
        }
    }

    /**
     * @throws Exception
     */
    private function createLoggerFile()
    {
        (new StubCompiler("LoggerService", "LoggerService"))
            ->save('services');

        $this->comment("LoggerService.php created");
    }
}
