<?php

namespace Alangiacomin\LaravelBasePack\Tests\Commands;

use Alangiacomin\LaravelBasePack\Commands\CommandHandler;
use Alangiacomin\LaravelBasePack\Tests\TestableCallables;
use Alangiacomin\LaravelBasePack\Tests\TestableModifiers;
use ReflectionException;

abstract class CommandHandlerTestable extends CommandHandler
{
    use TestableCallables, TestableModifiers;

    public $tries;

    public $maxExceptions;

    public $failOnTimeout;

    public $timeout;

    /**
     * @throws ReflectionException
     */
    public function __call(string $name, array $arguments)
    {
        return $this->callMethod($name, $arguments);
    }
}
