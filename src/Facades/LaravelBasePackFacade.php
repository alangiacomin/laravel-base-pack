<?php

namespace Alangiacomin\LaravelBasePack\Facades;

use Alangiacomin\LaravelBasePack\LaravelBasePack;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed callWithInjection(object $obj, string $method, array $params = [])
 * @method static mixed callStaticWithInjection(string $class, string $string, array $params = [])
 * @method static mixed injectedInstance(string $object)
 * @method static bool checkObjectType(mixed $object, string $string)
 * @method static bool checkObject(mixed $object, callable $callback)
 */
class LaravelBasePackFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LaravelBasePack::class;
    }
}
