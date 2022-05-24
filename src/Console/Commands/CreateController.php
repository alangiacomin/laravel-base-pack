<?php /** @noinspection PhpUnused */

namespace Alangiacomin\LaravelBasePack\Console\Commands;

use Alangiacomin\LaravelBasePack\Console\Commands\Core\Command;
use Alangiacomin\LaravelBasePack\Console\Commands\Core\StubCompiler;
use Exception;
use Illuminate\Support\Facades\Config;

class CreateController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'basepack:controller {name} {--r|resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Controller class';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function handleCommand()
    {
        $name = $this->argument('name');

        $this->createControllerFile($name, $this->option('resource'));

        if ($this->option('resource'))
        {
            $this->addWebRoute($name);
        }
    }

    /**
     * @param  string  $name
     * @param  bool  $resource
     * @throws Exception
     */
    private function createControllerFile(string $name, bool $resource)
    {
        $stubName = $resource ? "ResourceController" : "Controller";
        (new StubCompiler($stubName, "{$name}Controller"))
            ->replace("namespace", Config::get('basepack.namespaces.controllers'))
            ->replace("modelNamespace", Config::get('basepack.namespaces.models'))
            ->replace("repositoryNamespace", Config::get('basepack.namespaces.repositories'))
            ->replace("name", $name)
            ->replace("nameVar", lcfirst($name))
            ->save('controllers');

        $this->comment("{$name}Controller.php created");
    }

    private function addWebRoute(string $name)
    {
        $path = "'/".lcfirst($name)."'";
        $controller = "\\".Config::get('basepack.namespaces.controllers')."\\".$name."Controller::class";
        $route = "Route::resource($path, $controller);";

        file_put_contents(base_path()."\\routes\\web.php", $route.PHP_EOL, FILE_APPEND);

    }
}
