<?php /** @noinspection PhpUnused */

namespace Alangiacomin\LaravelBasePack\Console\Commands;

use Alangiacomin\LaravelBasePack\Console\Commands\Core\Command;
use Alangiacomin\LaravelBasePack\Console\Commands\Core\StubCompiler;
use Exception;
use Illuminate\Support\Facades\Config;

class CreateRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'basepack:repository {name} {--m|model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Repository class';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function handleCommand()
    {
        $name = $this->argument('name');

        $this->createInterfaceFile($name);
        $this->createClassFile($name);

        if ($this->option('model'))
        {
            $this->call("make:model", [
                    "name" => $name,
            ]);
            $this->comment("$name.php created (class must extend Aggregate)");
        }
    }

    /**
     * @param $name
     * @return void
     * @throws Exception
     */
    private function createInterfaceFile($name)
    {
        (new StubCompiler("IRepository", "I{$name}Repository"))
            ->replace("namespace", Config::get('basepack.namespaces.repositories'))
            ->replace("modelNamespace", Config::get('basepack.namespaces.models'))
            ->replace("model", $name)
            ->replace("modelVar", lcfirst($name))
            ->save('repositories');

        $this->comment("I{$name}Repository.php created");
    }

    /**
     * @param $name
     * @return void
     * @throws Exception
     */
    private function createClassFile($name)
    {
        (new StubCompiler("Repository", "{$name}Repository"))
            ->replace("namespace", Config::get('basepack.namespaces.repositories'))
            ->replace("modelNamespace", Config::get('basepack.namespaces.models'))
            ->replace("model", $name)
            ->replace("modelVar", lcfirst($name))
            ->save('repositories');

        $this->comment("{$name}Repository.php created");
    }
}
