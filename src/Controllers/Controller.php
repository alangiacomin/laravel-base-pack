<?php

namespace Alangiacomin\LaravelBasePack\Controllers;

use Alangiacomin\LaravelBasePack\Bus\Bus;
use Alangiacomin\LaravelBasePack\Commands\Command;
use Alangiacomin\LaravelBasePack\Commands\CommandResult;
use Alangiacomin\LaravelBasePack\Commands\ICommand;
use Alangiacomin\LaravelBasePack\Facades\LaravelBasePackFacade;
use Alangiacomin\LaravelBasePack\Traits\HasBindingInjection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @method middleware(string $middleware)
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use HasBindingInjection;

    /**
     * Middleware to apply on methods
     * Key: the method
     * Value: array with middleware list
     */
    protected array $cfgMiddleware = [];

    /**
     * Permissions to check allowing method execution
     * Key: the method
     * Value: array with permission list
     */
    protected array $cfgPermission = [];

    /**
     * Controller constructor
     */
    public function __construct()
    {
        $this->injectProps();

        $middlewares = [
            ...$this->arrangeCfgMiddleware($this->cfgMiddleware ?? []),
            ...$this->arrangeCfgMiddleware(
                array_map(
                    function ($action) {
                        return array_map(
                            function ($perm) {
                                return "can:$perm->value";
                            },
                            $action ?? []
                        );
                    },
                    $this->cfgPermission ?? []
                )
            ),
        ];
        foreach ($middlewares as $mw => $actions) {
            $this->middleware($mw)->only($actions);
        }
    }

    /**
     * Arrange middleware list according to laravel format
     */
    private function arrangeCfgMiddleware($cfg): array
    {
        $middlewares = [];
        foreach (($cfg ?? []) as $action => $mwList) {
            foreach ($mwList as $mw) {
                $middlewares[$mw] = [
                    ...($middlewares[$mw] ?? []),
                    $action,
                ];
            }
        }

        return $middlewares;
    }

    /**
     * Execute {@see Command} over the bus
     */
    final public function execute(ICommand $command): CommandResult
    {
        return LaravelBasePackFacade::callStaticWithInjection(
            Bus::class,
            'executeCommand',
            ['command' => $command]
        );
    }

    /**
     * Send {@see Command} on the bus
     */
    final public function sendCommand(ICommand $command): void
    {
        LaravelBasePackFacade::callStaticWithInjection(
            Bus::class,
            'sendCommand',
            ['command' => $command]
        );
    }
}
