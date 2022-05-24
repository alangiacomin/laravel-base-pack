<?php

namespace Alangiacomin\LaravelBasePack\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        $configValue = config('basepack.eventListener.shouldDiscoverEvents');

        return isset($configValue) && is_bool($configValue)
            ? $configValue
            : true;
    }

    /**
     * Get the listener directories that should be used to discover events.
     *
     * @return array
     */
    protected function discoverEventsWithin(): array
    {
        $defaultValue = [];
        $configValue = config('basepack.eventListener.directories');
        if (is_string($configValue))
        {
            $configValue = [$configValue];
        }

        if (!isset($configValue) || !is_array($configValue))
        {
            return $defaultValue;
        }

        return array_map(function ($c) {
            $rp = realpath($c);
            return $rp == false
                ? $this->app->path($c)
                : $rp;
        }, $configValue);
    }
}
