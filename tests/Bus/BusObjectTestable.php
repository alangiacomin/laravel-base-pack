<?php

namespace Alangiacomin\LaravelBasePack\Tests\Bus;

use Alangiacomin\LaravelBasePack\Bus\BusObject;
use ReflectionMethod;

class BusObjectTestable extends BusObject
{
    /**
     * @throws \ReflectionException
     */
    public function assignNewId(): void
    {
        $method = new ReflectionMethod(BusObject::class, 'assignNewId');
        $method->setAccessible(true);
        $method->invoke($this);
    }
}
