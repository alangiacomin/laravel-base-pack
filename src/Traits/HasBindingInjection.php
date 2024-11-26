<?php

namespace Alangiacomin\LaravelBasePack\Traits;

use AlanGiacomin\LaravelBasePack\Repositories\IRepository;
use ReflectionClass;
use ReflectionProperty;

trait HasBindingInjection
{
    private function injectProps(): void
    {
        $props = array_filter(
            (new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PUBLIC),
            fn ($p) => $p->getType() && $this->isInjectable($p->getType()->getName())
        );

        foreach ($props as $prop) {
            $pName = $prop->getName();
            $pClass = $prop->getType()->getName();
            $this->$pName = app($pClass);
        }
    }

    private function isInjectable(string $class): bool
    {
        return is_a($class, IRepository::class, true);
    }
}
