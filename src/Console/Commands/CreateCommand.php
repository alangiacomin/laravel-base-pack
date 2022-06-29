<?php /** @noinspection PhpUnused */

namespace Alangiacomin\LaravelBasePack\Console\Commands;

use Alangiacomin\LaravelBasePack\Console\Commands\Core\Command;
use Alangiacomin\LaravelBasePack\Console\Commands\Core\StubCompiler;
use Exception;
use Illuminate\Support\Facades\Config;

class CreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'basepack:command {name} {--a|aggregate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Command and CommandHandler classes';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function handleCommand()
    {
        $commandName = $this->argument('name');
        $aggregate = $this->option('aggregate');
        $aggregateName = null;

        if ($aggregate)
        {
            $aggregateName = $this->ask('Aggregate name');
            if (empty($aggregateName))
            {
                throw new Exception("Aggregate name not defined");
            }
        }

        $this->createCommandFile($commandName, $aggregate);
        $this->createCommandHandlerFile($commandName, $aggregateName);
        $this->createCommandRuleFile($commandName);
    }

    /**
     * @throws Exception
     */
    private function createCommandFile($name, $aggregate): void
    {
        (new StubCompiler("Command", $name))
            ->replace("namespace", Config::get('basepack.namespaces.commands'))
            ->replace("name", $name)
            ->replace("aggregate", $aggregate ? "Aggregate" : '')
            ->save('commands');

        $this->comment("$name.php created");
    }

    /**
     * @throws Exception
     */
    private function createCommandHandlerFile($name, $aggregate = null): void
    {
        $stubName = !empty($aggregate) ? "AggregateCommandHandler" : "CommandHandler";
        (new StubCompiler($stubName, "{$name}Handler"))
            ->replace("handlerNamespace", Config::get('basepack.namespaces.commandHandlers'))
            ->replace("commandNamespace", Config::get('basepack.namespaces.commands'))
            ->replace("repositoryNamespace", Config::get('basepack.namespaces.repositories'))
            ->replace("modelNamespace", Config::get('basepack.namespaces.models'))
            ->replace("command", $name)
            ->replace("aggregate", $aggregate ?? '')
            ->save('commandHandlers');

        $this->comment("{$name}Handler.php created");
    }

    /**
     * @throws Exception
     */
    private function createCommandRuleFile($name): void
    {
        (new StubCompiler("CommandRule", "{$name}Rule"))
            ->replace("handlerNamespace", Config::get('basepack.namespaces.commandHandlers'))
            ->replace("commandNamespace", Config::get('basepack.namespaces.commands'))
            ->replace("command", $name)
            ->save('commandHandlers');

        $this->comment("{$name}Rule.php created");
    }
}
