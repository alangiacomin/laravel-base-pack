<?php

namespace Alangiacomin\LaravelBasePack\Providers;

use Alangiacomin\LaravelBasePack\Core\Enums\BindingType;
use Alangiacomin\LaravelBasePack\Facades\LaravelBasePackFacade;
use Exception;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register repository bindings
     *
     * @return void
     * @throws Exception
     */
    public function register()
    {
        $bindings = config('basepack.bindings', []);
        if (!is_array($bindings))
        {
            throw new Exception("Bindings must be 'array', but '".gettype($bindings)."' found");
        }

        foreach ($bindings as $bind)
        {
            if (is_string($bind))
            {
                $bind = [
                    'interface' => $bind,
                    'bindingType' => BindingType::Bind,
                ];
            }

            LaravelBasePackFacade::checkObjectType($bind, 'array');

            $interface = $bind['interface'];
            $bindingType = $bind['bindingType'] ?? BindingType::Bind;

            LaravelBasePackFacade::checkObjectType($interface, 'string');
            LaravelBasePackFacade::checkObject($bindingType, function ($obj) use (&$bindingType) {
                if (is_string($obj))
                {
                    $bindingType = BindingType::from($obj);
                    return true;
                }
                return $obj instanceof BindingType;
            });

            $class = $bind['class'] ?? '';
            LaravelBasePackFacade::checkObjectType($class, 'string');
            if (empty($class))
            {
                $interfaceTokens = explode('\\', $interface);
                $lastToken = array_pop($interfaceTokens);
                $class = implode('\\', array_merge($interfaceTokens, [substr($lastToken, 1)]));
            }
            if (!class_exists($class))
            {
                throw new Exception("Binding: $class not found");
            }

            $this->app->{$bindingType->value}($interface, $class);
        }
    }
}
