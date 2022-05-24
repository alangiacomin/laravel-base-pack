<?php

namespace Alangiacomin\LaravelBasePack\Controllers;

use Alangiacomin\LaravelBasePack\Bus\Bus;
use Alangiacomin\LaravelBasePack\Commands\Command;
use Alangiacomin\LaravelBasePack\Commands\ICommand;
use Alangiacomin\LaravelBasePack\Facades\LaravelBasePackFacade;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @method middleware(string $strtolower)
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $controllerName = $this->getName();

        foreach (($this->restrictedMethods ?? []) as $rm)
        {
            $this->middleware(strtolower("permissions:{$controllerName}-{$rm}"))->only($rm);
        }

        foreach (($this->jsonResponse ?? []) as $jr)
        {
            $this->middleware("JsonResponse")->only($jr);
        }
    }

    /**
     * Execute {@see Command} over the bus
     *
     * @param  ICommand  $command
     * @return mixed
     */
    final public function execute(ICommand $command): mixed
    {
        return LaravelBasePackFacade::callStaticWithInjection(
            Bus::class,
            'executeCommand',
            ['command' => $command]);
    }

    /**
     * Send {@see Command} on the bus
     *
     * @param  ICommand  $command
     * @return void
     */
    final public function sendCommand(ICommand $command): void
    {
        LaravelBasePackFacade::callStaticWithInjection(
            Bus::class,
            'sendCommand',
            ['command' => $command]);
    }

    private function getName()
    {
        return str_replace("Controller", "", $this->getFullname());
    }

    private function getFullname()
    {
        $classNameWithNamespace = get_class($this);
        return substr($classNameWithNamespace, strrpos($classNameWithNamespace, "\\") + 1);
    }
}
