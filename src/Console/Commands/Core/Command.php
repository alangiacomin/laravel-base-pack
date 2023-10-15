<?php

/** @noinspection PhpUnused */

namespace Alangiacomin\LaravelBasePack\Console\Commands\Core;

use Illuminate\Console\Command as ConsoleCommand;
use Illuminate\Support\Arr;
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
        try {
            $this->handleCommand();
            $this->info('Done!');
        } catch (Throwable $th) {
            $this->newline();
            $this->error('Failed');
            $this->newline();
            $this->line($th->getMessage());
        }
    }

    abstract public function handleCommand(): void;

    /**
     * Gets the element name
     *
     * @param  string  $namespace Element full name
     * @return string Element name
     */
    protected function elementName(string $namespace): string
    {
        $normalized = $this->normalizeNamespaceSlash($namespace);

        return Arr::last(explode('\\', $normalized));
    }

    /**
     * Returns namespace with backslashes
     *
     * @param  string  $text Namespace
     * @return string Normalized namespace
     */
    protected function normalizeNamespaceSlash(string $text): string
    {
        return str_replace('/', '\\', $text);
    }

    /**
     * Returns path with slashes
     *
     * @param  string  $text Path
     * @return string Normalized path
     */
    protected function normalizePathSlash(string $text): string
    {
        return str_replace('\\', '/', $text);
    }

    /**
     * Gets namespace from element full name
     *
     * @param  string  $namespace Element full name
     * @return string Namespace
     */
    protected function relativeNamespace(string $namespace): string
    {
        $normalized = $this->normalizeNamespaceSlash($namespace);
        $relative = implode('\\', array_slice(explode('\\', $normalized), 0, -1));

        return empty($relative)
            ? ''
            : "\\${relative}";
    }
}
