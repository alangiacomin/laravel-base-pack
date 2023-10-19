<?php

namespace Alangiacomin\LaravelBasePack\Tests\Bus;

use Alangiacomin\LaravelBasePack\Events\EventHandler;
use Alangiacomin\LaravelBasePack\Tests\TestableCallables;
use Alangiacomin\LaravelBasePack\Tests\TestableModifiers;

class SimpleEventHandlerTestable extends EventHandler
{
    use TestableCallables, TestableModifiers;

    public function __call(string $name, array $arguments)
    {
        return $this->callMethod($name, $arguments);
    }

    protected function execute()
    {
    }
}
