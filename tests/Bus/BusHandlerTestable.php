<?php

namespace Alangiacomin\LaravelBasePack\Tests\Bus;

use Alangiacomin\LaravelBasePack\Bus\BusHandler;
use Alangiacomin\LaravelBasePack\Tests\TestableCallables;
use Alangiacomin\LaravelBasePack\Tests\TestableModifiers;

class BusHandlerTestable extends BusHandler
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
