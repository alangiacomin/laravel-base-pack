<?php

namespace Alangiacomin\LaravelBasePack\Facades;

use Alangiacomin\LaravelBasePack\LaravelBasePack;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed callWithInjection(object $obj, string $method, array $params = [])
 * @method static mixed injectedInstance(string $object)
 */
class LaravelBasePackFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LaravelBasePack::class;
    }
}
