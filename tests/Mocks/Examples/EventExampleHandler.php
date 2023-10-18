<?php

namespace Alangiacomin\LaravelBasePack\Tests\Mocks\Examples;

use Alangiacomin\LaravelBasePack\Bus\BusObject;
use Alangiacomin\LaravelBasePack\Events\EventHandler;
use Alangiacomin\LaravelBasePack\Tests\TestableCallables;
use Alangiacomin\LaravelBasePack\Tests\TestableModifiers;
use ReflectionException;

class EventExampleHandler extends EventHandler
{
    use TestableCallables, TestableModifiers;

    public BusObject $event;

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
