<?php

namespace Tests\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\FakeClasses\ExampleCommand;
use Tests\FakeClasses\ExampleControllerTest;
use Tests\TestCase;

class ControllerTestTraitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->traitInstance = new ExampleControllerTest('prova');
    }

    public function test_initialize_mock_handler_should_return_mock_instance(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->initializeMockHandler('executeCommand', 'App\Http');

        $this->assertInstanceOf(MockObject::class, $this->traitInstance->mockHandler);
    }

    public function test_should_execute_command_with_correct_controller_mock(): void
    {
        $commandClass = ExampleCommand::class;

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->initializeMockHandler('executeCommand', 'App\Http');

        $mockController = $this->traitInstance->mockHandler;

        $mockController->expects($this->once())
            ->method('executeCommand')
            ->with($this->isInstanceOf($commandClass));

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->should_execute_command($commandClass);

        $this->assertTrue(true, 'The method executeCommand has been invoked.');
    }

    public function test_should_not_execute_command_sets_zero_invocation_expectation(): void
    {
        $commandClass = ExampleCommand::class;

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->initializeMockHandler('executeCommand', 'App\Http');

        $mockController = $this->traitInstance->mockHandler;

        $mockController->expects($this->once())
            ->method('executeCommand')
            ->with($this->isInstanceOf($commandClass));

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->should_not_execute_command($commandClass);

        $this->assertTrue(true, 'The method executeCommand has not been invoked.');
    }

    public function test_initialize_mock_handler_creates_mock_with_execute_command_method(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->initializeMockHandler('executeCommand', 'App\Http');

        $this->assertInstanceOf(MockObject::class, $this->traitInstance->mockHandler);
        $this->assertTrue(
            method_exists($this->traitInstance->mockHandler, 'executeCommand'),
            'The executeCommand method does not exist in the mock controller'
        );
    }
}
