<?php

namespace Alangiacomin\LaravelBasePack\Tests\Bus;

use Alangiacomin\LaravelBasePack\Events\EventHandler;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\EventExample;
use Alangiacomin\LaravelBasePack\Tests\TestableCallables;
use Alangiacomin\LaravelBasePack\Tests\TestableModifiers;

class BusEventHandlerTestable extends EventHandler
{
    use TestableCallables, TestableModifiers;

    public EventExample $event;

    public function __call(string $name, array $arguments)
    {
        return $this->callMethod($name, $arguments);
    }

    protected function execute()
    {
    }
}
