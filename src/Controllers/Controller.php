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
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

/**
 * @method middleware(string $middleware)
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use HasBindingInjection;

    /**
     * Controller constructor
     */
    public function __construct()
    {
        $this->injectProps();
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
    final public function send(ICommand $command): void
    {
        LaravelBasePackFacade::callStaticWithInjection(
            Bus::class,
            'sendCommand',
            ['command' => $command]
        );
    }

    /**
     * Converts a {@see CommandResult} to a {@see JsonResponse}
     *
     * @param  CommandResult  $result The result
     * @return JsonResponse The response
     */
    protected function jsonResponse(CommandResult $result): JsonResponse
    {
        return response()->json($result);
    }
}
