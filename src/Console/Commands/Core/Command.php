<?php

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

    abstract public function handleCommand(): void;

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

    protected function deleteFile(string $file): void
    {
        if (file_exists($file)) {
            unlink($file);
        }
    }

    protected function copyFile(string $src, string $dest, array $search = [], array $replace = []): void
    {
        if (!file_exists($src) || file_exists($dest)) {
            return;
        }

        $dirName = pathinfo($dest, PATHINFO_DIRNAME);
        $this->mkdirIfMissing($dirName);

        copy($src, $dest);

        $this->replaceInFile($dest, $search, $replace);
    }

    protected function moveFile(string $src, string $dest, array $search = [], array $replace = []): void
    {
        if (!file_exists($src) || file_exists($dest)) {
            return;
        }

        $dirName = pathinfo($dest, PATHINFO_DIRNAME);
        $this->mkdirIfMissing($dirName);

        rename($src, $dest);

        $this->replaceInFile($dest, $search, $replace);
    }

    protected function replaceInFile(string $src, array $search = [], array $replace = []): void
    {
        file_put_contents($src, str_replace($search, $replace, file_get_contents($src)));
    }

    protected function mkdirIfMissing(string $dir): void
    {
        if (!is_dir($dir)) {
            mkdir($dir);
        }
    }
}