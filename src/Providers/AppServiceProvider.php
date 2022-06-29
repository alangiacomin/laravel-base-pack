<?php

namespace Alangiacomin\LaravelBasePack\Providers;

use Alangiacomin\LaravelBasePack\Core\Enums\BindingType;
use Alangiacomin\LaravelBasePack\Facades\LaravelBasePackFacade;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var BindingType
     */
    private BindingType $bindingDefaultType;

    /**
     * @var array
     */
    private array $bindingsList;

    /**
     * @var array
     */
    private array $registeredBindings;

    /**
     * Register repository bindings
     *
     * @return void
     * @throws Exception
     */
    public function register(): void
    {
        // initial values
        $this->registeredBindings = [];
        $this->bindingDefaultType = config('basepack.bindings.defaultType', BindingType::Bind);
        $this->bindingsList = config('basepack.bindings.list', []);

        $this->registerBindingsConfig();
        $this->registerBindingsAuto();
    }

    /**
     * @throws Exception
     */
    public function registerBindingsConfig()
    {
        foreach ($this->bindingsList as $bind)
        {
            $bind = $this->normalizeBind($bind);
            LaravelBasePackFacade::checkObjectType($bind, 'array');
            LaravelBasePackFacade::checkObjectType($bind['interface'], 'string');
            LaravelBasePackFacade::checkObject($bind['bindingType'], function ($obj) use (&$bind) {
                if (is_string($obj))
                {
                    $bind['bindingType'] = BindingType::from($obj);
                    return true;
                }
                return $obj instanceof BindingType;
            });

            if (empty($bind['class']))
            {
                $bind['class'] = $this->classNameFromInterface($bind['interface']);
            }
            LaravelBasePackFacade::checkObjectType($bind['class'], 'string');

            if (!class_exists($bind['class']))
            {
                throw new Exception("Binding: {$bind['class']} not found");
            }

            $this->app->{$bind['bindingType']->value}($bind['interface'], $bind['class']);
            $this->registeredBindings[] = $bind['interface'];
        }
    }

    public function registerBindingsAuto()
    {
        $autoBindings = [
            [
                'folder' => app_path("repositories"),
                'interface' => 'Alangiacomin\LaravelBasePack\Repositories\IRepository',
                'namespace' => config('basepack.namespaces.repositories'),
            ],
            [
                'folder' => app_path("services"),
                'interface' => 'Alangiacomin\LaravelBasePack\Services\IService',
                'namespace' => config('basepack.namespaces.services'),
            ],
        ];

        // include all interfaces
        foreach (array_column($autoBindings, 'folder') as $folder)
        {
            if (is_dir($folder))
            {
                foreach (scandir($folder) as $ff)
                {
                    $fullPath = "{$folder}\\{$ff}";
                    if (is_file($fullPath) && Str::startsWith($ff, "I"))
                    {
                        include_once $fullPath;
                    }
                }
            }
        }

        // register interfaces
        $declaredInterfaces = array_filter(get_declared_interfaces(), function ($i) {
            return Str::contains($i, '\\')
                && !Str::startsWith($i, 'Illuminate\\')
                && !Str::startsWith($i, 'Dotenv\\')
                && !Str::startsWith($i, 'Psr\\')
                && !Str::startsWith($i, 'Laravel\\')
                && !Str::startsWith($i, 'Alangiacomin\\')
                && !Str::startsWith($i, 'Symfony\\');
        });

        foreach ($declaredInterfaces as $di)
        {
            if (in_array($di, $this->registeredBindings))
            {
                continue;
            }

            $implementedInterfaces = class_implements($di);

            foreach ($autoBindings as $ab)
            {
                if (in_array($ab['interface'], $implementedInterfaces)
                    && Str::startsWith($di, $ab['namespace']))
                {
                    $class = $this->classNameFromInterface($di);
                    if (class_exists($class))
                    {
                        $this->app->{$this->bindingDefaultType->value}($di, $class);
                        $this->registeredBindings[] = $di;
                    }
                    break;
                }
            }
        }
    }

    /**
     * @param $bind
     * @return array
     */
    private function normalizeBind($bind): array
    {
        if (is_string($bind))
        {
            return [
                'interface' => $bind,
                'bindingType' => $this->bindingDefaultType,
            ];
        }
        $bind['bindingType'] ??= $this->bindingDefaultType;
        return $bind;
    }

    /**
     * @param $interface
     * @return string
     */
    private function classNameFromInterface($interface): string
    {
        $tokens = explode('\\', $interface);
        $lastToken = array_pop($tokens);
        return implode('\\', array_merge($tokens, [substr($lastToken, 1)]));
    }
}
