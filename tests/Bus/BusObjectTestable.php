<?php

namespace Alangiacomin\LaravelBasePack\Tests\Bus;

use Alangiacomin\LaravelBasePack\Bus\BusObject;
use Alangiacomin\LaravelBasePack\Tests\TestableCallables;
use ReflectionException;

class BusObjectTestable extends BusObject
{
    use TestableCallables;

    public string $first = '';

    public string $second = '';

    /**
     * @throws ReflectionException
     */
    public function __call(string $name, array $arguments)
    {
        return $this->callMethod($name, $arguments);
    }
}
