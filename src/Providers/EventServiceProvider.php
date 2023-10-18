<?php

namespace Alangiacomin\LaravelBasePack\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }

    /**
     * Get the listener directories that should be used to discover events.
     */
    protected function discoverEventsWithin(): array
    {
        $directories = [
            'Events',
        ];

        return array_map(
            function ($c) {
                $rp = realpath($c);

                return !$rp
                    ? $this->app->path($c)
                    : $rp;
            },
            $directories
        );
    }
}
