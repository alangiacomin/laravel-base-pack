<?php

namespace Tests\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\FakeClasses\ExampleCommand;
use Tests\FakeClasses\ExampleEventHandlerTest;
use Tests\TestCase;

class EventHandlerTestTraitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->traitInstance = new ExampleEventHandlerTest('prova');
    }

    public function test_initialize_mock_handler_should_return_mock_instance(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->initializeMockHandler('execute', 'App');

        $this->assertInstanceOf(MockObject::class, $this->traitInstance->mockHandler);
    }

    public function test_should_handle_event_with_correct_event_class(): void
    {
        $commandClass = ExampleCommand::class;

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->initializeMockHandler('execute', 'App');

        $mockHandler = $this->traitInstance->mockHandler;

        $mockHandler->expects($this->once())
            ->method('execute')
            ->with($this->isInstanceOf($commandClass));

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->should_send_command($commandClass);

        $this->assertTrue(true, 'The method execute has been invoked.');
    }

    public function test_should_not_send_command_sets_zero_invocation_expectation(): void
    {
        $commandClass = ExampleCommand::class;

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->initializeMockHandler('execute', 'App');

        $mockHandler = $this->traitInstance->mockHandler;

        $mockHandler->expects($this->once())
            ->method('execute')
            ->with($this->isInstanceOf($commandClass));

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->should_not_send_command($commandClass);
        $this->traitInstance->should_send_command($commandClass);

        $this->assertTrue(true, 'The method execute has been invoked.');
    }

    public function test_initialize_mock_handler_creates_mock_with_execute_method(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->initializeMockHandler('execute', 'App');

        $this->assertInstanceOf(MockObject::class, $this->traitInstance->mockHandler);
        $this->assertTrue(
            method_exists($this->traitInstance->mockHandler, 'execute'),
            'The execute method does not exist in the mock handler'
        );
    }
}
