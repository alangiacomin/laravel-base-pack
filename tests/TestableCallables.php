<?php

namespace Alangiacomin\LaravelBasePack\Tests;

use ReflectionException;
use ReflectionMethod;

trait TestableCallables
{
    /**
     * @throws ReflectionException
     */
    public function callMethod(string $name, array $arguments)
    {
        $method =
            method_exists($this, $name)
                ? new ReflectionMethod($this, $name)
                : new ReflectionMethod(get_parent_class($this), $name);

        if (!$method->isPublic()) {
            /** @noinspection PhpExpressionResultUnusedInspection */
            $method->setAccessible(true);
            try {
                return $method->invoke($this, ...$arguments);
            } catch (ReflectionException) {
                return null;
            }
        }

        return call_user_func(['parent', $name], ...$arguments);
    }
}
