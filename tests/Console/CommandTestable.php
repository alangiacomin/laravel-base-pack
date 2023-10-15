<?php

namespace Alangiacomin\LaravelBasePack\Tests\Console;

use Alangiacomin\LaravelBasePack\Console\Commands\Core\Command;
use Alangiacomin\LaravelBasePack\Tests\TestableCallables;
use Alangiacomin\LaravelBasePack\Tests\TestableModifiers;
use ReflectionException;

class CommandTestable extends Command
{
    use TestableCallables, TestableModifiers;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws ReflectionException
     */
    public function __call($method, $parameters)
    {
        return $this->callMethod($method, $parameters);
    }

    public function handleCommand(): void
    {
    }
}
