<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Alangiacomin\LaravelBasePack\Tests\Bus;

use Alangiacomin\LaravelBasePack\Bus\Bus;
use Alangiacomin\LaravelBasePack\Facades\LaravelBasePackFacade;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandExample;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\EventExample;
use Alangiacomin\LaravelBasePack\Tests\Mocks\LaravelBasePackFacadeMock;
use Alangiacomin\LaravelBasePack\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class BusTests extends TestCase
{
    /*******************/
    /* EXECUTE COMMAND */
    /*******************/

    public function test_executeCommand_ok()
    {
        new LaravelBasePackFacadeMock();
        LaravelBasePackFacade::shouldReceive('callWithInjection')
            ->withSomeOfArgs('execute')
            ->once();

        Bus::executeCommand(new CommandExample());
    }

    /****************/
    /* SEND COMMAND */
    /****************/

    public function test_sendCommand_ok()
    {
        new LaravelBasePackFacadeMock();
        LaravelBasePackFacade::shouldReceive('callWithInjection')
            ->withSomeOfArgs('execute')
            ->once();

        Bus::sendCommand(new CommandExample());
    }

    /*****************/
    /* PUBLISH EVENT */
    /*****************/

    public function test_publishEvent_ok()
    {
        Event::fake();

        Bus::publishEvent(new EventExample());

        Event::assertDispatched(EventExample::class);
    }
}
