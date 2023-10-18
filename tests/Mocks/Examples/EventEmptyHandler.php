<?php

namespace Alangiacomin\LaravelBasePack\Tests\Mocks\Examples;

use Alangiacomin\LaravelBasePack\Events\EventHandler;
use Alangiacomin\LaravelBasePack\Tests\TestableCallables;
use Alangiacomin\LaravelBasePack\Tests\TestableModifiers;

class EventEmptyHandler extends EventHandler
{
    use TestableCallables, TestableModifiers;

    public function __call(string $name, array $arguments)
    {
        return $this->call_user_defined($name, $arguments);
    }

    protected function execute()
    {
    }
}
