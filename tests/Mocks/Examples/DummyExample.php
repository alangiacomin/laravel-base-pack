<?php

namespace Alangiacomin\LaravelBasePack\Tests\Mocks\Examples;

use Illuminate\Http\Request;

class DummyExample
{
    public Request $instanceRequest;

    public static Request $staticRequest;

    public static function staticWithoutParams(Request $request)
    {
        self::$staticRequest = $request;
    }

    public static function staticWithParams(string $param, Request $request)
    {
        self::$staticRequest = $request;
    }

    public function withoutParams(Request $request)
    {
        $this->instanceRequest = $request;
    }

    public function withParams(string $param, Request $request)
    {
        $this->instanceRequest = $request;
    }
}
