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

    public function test_should_execute_the_set_mock_method_and_mock_the_controller(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->initializeMockController();

        $this->assertInstanceOf(MockObject::class, $this->traitInstance->mockController);
    }

    public function test_should_execute_command_calls_execute_command_with_correct_command_class(): void
    {
        $commandClass = ExampleCommand::class;

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->initializeMockController();

        $mockController = $this->traitInstance->mockController;

        $mockController->expects($this->once())
            ->method('executeCommand')
            ->with($this->isInstanceOf($commandClass));

        $this->traitInstance->should_execute_command($commandClass, $this->exactly(1));

        $this->assertTrue(true, 'The method executeCommand has been invoked.');
    }

    public function test_initialize_mock_controller_creates_correct_mock_object(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->initializeMockController();

        $this->assertInstanceOf(MockObject::class, $this->traitInstance->mockController);
        $this->assertTrue(
            method_exists($this->traitInstance->mockController, 'executeCommand'),
            'The executeCommand method does not exist in the mock controller'
        );
    }
}
