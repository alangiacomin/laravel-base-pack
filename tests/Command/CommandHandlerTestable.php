<?php

namespace Alangiacomin\LaravelBasePack\Tests\Command;

use Alangiacomin\LaravelBasePack\Commands\CommandHandler;
use Alangiacomin\LaravelBasePack\Tests\TestableCallables;
use Alangiacomin\LaravelBasePack\Tests\TestableModifiers;
use ReflectionException;

abstract class CommandHandlerTestable extends CommandHandler
{
    use TestableCallables, TestableModifiers;

    /**
     * @throws ReflectionException
     */
    public function __call(string $name, array $arguments)
    {
        return $this->callMethod($name, $arguments);
    }
}
