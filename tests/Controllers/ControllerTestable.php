<?php

namespace Alangiacomin\LaravelBasePack\Tests\Controllers;

use Alangiacomin\LaravelBasePack\Controllers\Controller;
use Alangiacomin\LaravelBasePack\Tests\TestableCallables;
use Alangiacomin\LaravelBasePack\Tests\TestableModifiers;
use Exception;
use ReflectionException;

class ControllerTestable extends Controller
{
    use TestableCallables, TestableModifiers;

    /**
     * @throws ReflectionException
     */
    public function __call($method, $parameters)
    {
        try {
            return parent::__call($method, $parameters);
        } catch (Exception) {
        }

        return $this->callMethod($method, $parameters);
    }
}
