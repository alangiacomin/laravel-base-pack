<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Alangiacomin\LaravelBasePack\Tests\Bus;

use Alangiacomin\LaravelBasePack\Bus\BusHandler;
use Alangiacomin\LaravelBasePack\Exceptions\BusException;
use Alangiacomin\LaravelBasePack\Facades\LaravelBasePackFacade;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandExample;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandExampleHandler;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandWithExceptionExample;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandWithExceptionExampleHandler;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\EventExample;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\EventExampleHandler;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\JobExample;
use Alangiacomin\LaravelBasePack\Tests\Mocks\LaravelBasePackFacadeMock;
use Alangiacomin\LaravelBasePack\Tests\TestCase;
use Exception;

class BusHandlerTests extends TestCase
{
    public BusHandler $handler;

    public CommandExample $command;

    public EventExample $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->command = new CommandExample();
        $this->event = new EventExample();

        $this->handler = new BusCommandHandlerTestable($this->command);
    }

    /**********/
    /* FAILED */
    /**********/

    public function test_failed_default()
    {
        $this->handler->isActive = true;

        $this->handler->failed(new Exception());

        expect($this->handler->isActive)->toBeFalse();
    }

    /*****************/
    /* HANDLE OBJECT */
    /*****************/

    public function test_handleObject_event()
    {
        $this->handler = new BusEventHandlerTestable();

        $this->handler->handleObject($this->event);

        expect($this->handler->busObject)->toBe($this->event);
    }

    public function test_handleObject_command()
    {
        $this->handler = new BusCommandHandlerTestable($this->command);

        $this->handler->handleObject($this->command);

        expect($this->handler->busObject)->toBe($this->command);
    }

    public function test_handleObject_max_retries()
    {
        $this->handler = new CommandWithExceptionExampleHandler(new CommandWithExceptionExample());
        $this->handler->job = new JobExample('otherQueue');
        $this->handler->maxRetries = 5;

        new LaravelBasePackFacadeMock();
        LaravelBasePackFacade::shouldReceive('callWithInjection')
            ->withSomeOfArgs('failed')
            ->times($this->handler->maxRetries);
        $this->handler->handleObject($this->command);
    }

    /***************************/
    /* HANDLE OBJECT EXECUTION */
    /***************************/

    public function test_handleObjectExecution_default()
    {
        $this->handler = new CommandExampleHandler(new CommandExample());

        new LaravelBasePackFacadeMock();
        LaravelBasePackFacade::shouldReceive('callWithInjection')
            ->withSomeOfArgs('execute')
            ->once();
        $this->handler->handleObjectExecution();
    }

    /********************/
    /* SET TYPED OBJECT */
    /********************/

    public function test_setTypedObject_command_ok()
    {
        $this->handler = new CommandExampleHandler($this->command);
        $this->handler->busObject = $this->command;

        $this->handler->setTypedObject();

        expect($this->handler->command)->toBe($this->command);
    }

    public function test_setTypedObject_command_error()
    {
        $this->handler = new SimpleCommandHandlerTestable($this->command);
        $this->handler->busObject = $this->command;

        $this->shouldThrowException(
            fn () => $this->handler->setTypedObject(),
            BusException::class,
            "Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandExample: 'command' property must be defined"
        );
    }

    public function test_setTypedObject_event_ok()
    {
        $this->handler = new EventExampleHandler();
        $this->handler->busObject = $this->event;

        $this->handler->setTypedObject();

        expect($this->handler->event)->toBe($this->event);
    }

    public function test_setTypedObject_event_error()
    {
        $this->handler = new SimpleEventHandlerTestable();
        $this->handler->busObject = $this->event;

        $this->shouldThrowException(
            fn () => $this->handler->setTypedObject(),
            BusException::class,
            "Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\EventExample: 'event' property must be defined"
        );
    }

    public function test_setTypedObject_error()
    {
        $this->handler = new BusHandlerTestable();

        $this->shouldThrowException(
            fn () => $this->handler->setTypedObject(),
            BusException::class,
            "Alangiacomin\LaravelBasePack\Tests\Bus\BusHandlerTestable: Handler must be a 'CommandHandler' or an 'EventHandler'"
        );
    }
}
