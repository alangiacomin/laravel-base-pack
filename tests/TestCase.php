<?php

namespace Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function app_path_os(string $path): string
    {
        return $this->path_os(app_path($path));
    }

    protected function path_os(string $path): string
    {
        return str_replace('/', DIRECTORY_SEPARATOR, $path);
    }
}
