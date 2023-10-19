<?php

namespace Alangiacomin\LaravelBasePack\Tests\Providers;

use Alangiacomin\LaravelBasePack\Providers\EventServiceProvider;
use Alangiacomin\LaravelBasePack\Tests\TestableCallables;

class EventServiceProviderTestable extends EventServiceProvider
{
    use TestableCallables;

    public function __call(string $name, array $arguments)
    {
        return $this->callMethod($name, $arguments);
    }
}
