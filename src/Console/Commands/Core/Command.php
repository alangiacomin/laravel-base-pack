<?php

/** @noinspection PhpUnused */

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
     */
    public function handle(): void
    {
        $this->newline();

        try {
            $this->handleCommand();
            $this->info('Done!');
        } catch (Throwable $th) {
            $this->newline();
            $this->error('Failed');
            $this->newline();
            $this->line($th->getMessage());
        }

        $this->newline();
    }

    abstract public function handleCommand(): void;

    protected function printResult(string $successMessage, array $errors): void
    {
        if (empty($errors)) {
            $this->comment($successMessage);
        } else {
            foreach ($errors as $error) {
                $this->error($error);
            }
        }
    }
}
