<?php

namespace Alangiacomin\LaravelBasePack\Tests;

trait TestableModifiers
{
    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __set(string $name, mixed $value): void
    {
        $this->$name = $value;
    }
}
