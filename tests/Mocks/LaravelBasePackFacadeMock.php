<?php

namespace Alangiacomin\LaravelBasePack\Tests\Mocks;

use Alangiacomin\LaravelBasePack\Bus\Bus;
use Alangiacomin\LaravelBasePack\Commands\CommandResult;
use Alangiacomin\LaravelBasePack\Facades\LaravelBasePackFacade;
use Alangiacomin\LaravelBasePack\Services\EmptyBusMonitorService;
use Alangiacomin\LaravelBasePack\Services\IBusMonitorService;

class LaravelBasePackFacadeMock
{
    public function __construct()
    {
        LaravelBasePackFacade::shouldReceive('callWithInjection')->byDefault();

        LaravelBasePackFacade::shouldReceive('callStaticWithInjection')
            ->withSomeOfArgs(Bus::class, 'executeCommand')
            ->andReturn(
                new CommandResult()
            )
            ->byDefault();

        LaravelBasePackFacade::shouldReceive('callStaticWithInjection')
            ->withSomeOfArgs(Bus::class, 'sendCommand')
            ->byDefault();

        LaravelBasePackFacade::shouldReceive('received')->byDefault();

        //LaravelBasePackFacade::shouldReceive('injectedInstance')->with(IBusMonitorService::class)->andReturn(
        //    new EmptyBusMonitorService()
        //)->byDefault();
    }

    public function injectedInstance(string $class, mixed $returnObject): void
    {
        LaravelBasePackFacade::shouldReceive('injectedInstance')->with($class)->andReturn($returnObject);
    }
}
