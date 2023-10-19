<?php

namespace Alangiacomin\LaravelBasePack\Tests\Events;

use Alangiacomin\LaravelBasePack\Events\EventHandler;
use Alangiacomin\LaravelBasePack\Tests\TestableCallables;
use Alangiacomin\LaravelBasePack\Tests\TestableModifiers;
use ReflectionException;

class EventHandlerTestable extends EventHandler
{
    use TestableCallables, TestableModifiers;

    /**
     * @throws ReflectionException
     */
    public function __call(string $name, array $arguments)
    {
        return $this->callMethod($name, $arguments);
    }

    protected function execute()
    {
    }
}
