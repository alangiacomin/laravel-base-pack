<?php

use Alangiacomin\LaravelBasePack\Core\Enums\BindingType;
use Alangiacomin\LaravelBasePack\Services\EmptyLoggerService;
use Alangiacomin\LaravelBasePack\Services\ILoggerService;

return [

    /**
     * Namespaces
     */
    'namespaces' => [
        'commands' => 'App\Commands',
        'commandHandlers' => 'App\CommandHandlers',
        'controllers' => 'App\Http\Controllers',
        'events' => 'App\Events',
        'eventHandlers' => 'App\EventHandlers',
        'models' => 'App\Models',
        'repositories' => 'App\Repositories',
        'services' => 'App\Services',
    ],

    /**
     * Event listener configuration
     */
    'eventListener' => [
        // Overrides the default value
        'shouldDiscoverEvents' => true,
        // Additional directories
        'directories' => [
            'EventHandlers',
        ],
    ],

    /**
     * Binding configuration
     *
     * List bindings for automatic injection
     */
    'bindings' => [
        [
            'interface' => ILoggerService::class,
            'class' => EmptyLoggerService::class,
            'bindingType' => BindingType::Singleton,
        ],
    ],

    /**
     * Routes configuration
     */
    'routes' => [
        'fallback' => true,
        'fallbackView' => 'react',
    ],

    /**
     * Log configuration
     */
    'log' => [
        'uri' => env('LOGGER_URI', 'http://localhost:8999'),
    ],
];
