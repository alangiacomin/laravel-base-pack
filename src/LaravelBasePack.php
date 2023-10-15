<?php

namespace Alangiacomin\LaravelBasePack;

use Illuminate\Support\Facades\App;

class LaravelBasePack
{
    public function callWithInjection(object $obj, string $method, array $params = []): mixed
    {
        return App::call([$obj, $method], $params);
    }

    public function injectedInstance(string $object): mixed
    {
        return App::make($object);
    }
}
