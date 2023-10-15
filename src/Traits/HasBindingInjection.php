<?php

namespace Alangiacomin\LaravelBasePack\Traits;

use Alangiacomin\LaravelBasePack\Facades\LaravelBasePackFacade;
use Alangiacomin\PhpUtils\ArrayUtility;
use ReflectionClass;
use ReflectionProperty;

trait HasBindingInjection
{
    private function injectProps(): void
    {
        $props = array_filter(
            (new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PUBLIC),
            fn ($p) => $p->getType() && $this->is_injectable($p->getType()->getName())
        );

        foreach ($props as $prop) {
            $pName = $prop->getName();
            $pClass = $prop->getType()->getName();
            $this->$pName = LaravelBasePackFacade::injectedInstance($pClass);
        }
    }

    private function is_injectable(string $objectClass): bool
    {
        $autoBindings = [
            // [
            //     // 'folder' => base_path("vendor/alangiacomin/laravel-base-pack/src/Repositories"),
            //     'interface' => 'Alangiacomin\LaravelBasePack\Repositories\IRepository',
            //     // 'namespace' => 'Alangiacomin\LaravelBasePack\Repositories',
            // ],
            // [
            //     // 'folder' => app_path("Repositories"),
            //     'interface' => 'Alangiacomin\LaravelBasePack\Repositories\IRepository',
            //     // 'namespace' => config('basepack.namespaces.repositories'),
            // ],
            // [
            //     // 'folder' => app_path("Services"),
            //     'interface' => 'Alangiacomin\LaravelBasePack\Services\IService',
            //     // 'namespace' => config('basepack.namespaces.services'),
            // ],
        ];

        // Merge automatic bindings with user defined ones
        $interfaces = array_unique(
            array_merge(
                array_column($autoBindings, 'interface'),
                // array_column(config('basepack.bindings'), 'interface')
            )
        );

        return ArrayUtility::any($interfaces, fn ($e) => is_a($objectClass, $e, true));
    }
}
