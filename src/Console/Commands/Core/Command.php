<?php /** @noinspection PhpUnused */

namespace Alangiacomin\LaravelBasePack\Console\Commands\Core;

use Illuminate\Console\Command as ConsoleCommand;
use Throwable;

abstract class Command extends ConsoleCommand
{
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        try
        {
            $this->handleCommand();
            $this->info("Done!");
        }
        catch (Throwable $th)
        {
            $this->newline();
            $this->error("Failed");
            $this->newline();
            $this->line($th->getMessage());
        }
    }

    public abstract function handleCommand();
}
