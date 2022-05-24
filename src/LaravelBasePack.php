<?php

namespace Alangiacomin\LaravelBasePack;

use Exception;
use Illuminate\Support\Facades\App;

class LaravelBasePack
{
    public function callWithInjection(object $obj, string $method, array $params = [])
    {
        return App::call([$obj, $method], $params);
    }

    public function callStaticWithInjection(string $class, string $method, array $params = [])
    {
        return App::call($class.'@'.$method, $params);
    }

    public function injectedInstance(string $object)
    {
        return App::make($object);
    }

    /**
     * @throws Exception
     */
    public function checkObjectType(mixed $object, string $type)
    {
        if (gettype($object) != $type)
        {
            throw new Exception("Object should be '$type', but '".gettype($object)."' found.");
        }
    }

    /**
     * @throws Exception
     */
    public function checkObject(mixed $object, callable $callback)
    {
        if (!$callback($object))
        {
            throw new Exception("Object check failed.");
        }
    }
}
