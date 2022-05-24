<?php /** @noinspection PhpUnused */

namespace Alangiacomin\LaravelBasePack\Console\Commands;

use Alangiacomin\LaravelBasePack\Console\Commands\Core\Command;
use Alangiacomin\LaravelBasePack\Console\Commands\Core\StubCompiler;
use Exception;
use Illuminate\Support\Facades\Config;

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
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function handleCommand()
    {
        $commandName = $this->argument('name');

        $this->createEventFile($commandName);
        $this->createEventHandlerFile($commandName);
    }

    /**
     * @param $name
     * @return void
     * @throws Exception
     */
    private function createEventFile($name)
    {
        (new StubCompiler("Event", "$name"))
            ->replace("namespace", Config::get('basepack.namespaces.events'))
            ->replace("name", "$name")
            ->save('events');

        $this->comment("$name.php created");
    }

    /**
     * @param $name
     * @return void
     * @throws Exception
     */
    private function createEventHandlerFile($name): void
    {
        (new StubCompiler("EventHandler", "{$name}Handler"))
            ->replace("handlerNamespace", Config::get('basepack.namespaces.eventHandlers'))
            ->replace("eventNamespace", Config::get('basepack.namespaces.events'))
            ->replace("event", $name)
            ->save('eventHandlers');

        $this->comment("{$name}Handler.php created");
    }
}
