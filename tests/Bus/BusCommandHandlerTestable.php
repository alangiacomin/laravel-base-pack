<?php

namespace Alangiacomin\LaravelBasePack\Tests\Bus;

use Alangiacomin\LaravelBasePack\Commands\CommandHandler;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandExample;
use Alangiacomin\LaravelBasePack\Tests\TestableCallables;
use Alangiacomin\LaravelBasePack\Tests\TestableModifiers;

class BusCommandHandlerTestable extends CommandHandler
{
    use TestableCallables, TestableModifiers;

    public CommandExample $command;

    public function __call(string $name, array $arguments)
    {
        return $this->callMethod($name, $arguments);
    }

    protected function execute()
    {
    }
}
