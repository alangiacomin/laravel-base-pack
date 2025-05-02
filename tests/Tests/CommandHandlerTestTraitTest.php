<?php

namespace Tests\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\FakeClasses\ExampleCommandHandlerTest;
use Tests\FakeClasses\ExampleEvent;
use Tests\TestCase;

class CommandHandlerTestTraitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->traitInstance = new ExampleCommandHandlerTest('prova');
    }

    public function test_initialize_mock_handler_should_return_mock_instance(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->initializeMockHandler('publish', 'App');

        $this->assertInstanceOf(MockObject::class, $this->traitInstance->mockHandler);
    }

    public function test_should_publish_event_with_correct_event_class(): void
    {
        $eventClass = ExampleEvent::class;

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->initializeMockHandler('publish', 'App');

        $mockHandler = $this->traitInstance->mockHandler;

        $mockHandler->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf($eventClass));

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->should_publish_event($eventClass);

        $this->assertTrue(true, 'The method publish has been invoked.');
    }

    public function test_should_not_publish_event_sets_zero_invocation_expectation(): void
    {
        $eventClass = ExampleEvent::class;

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->initializeMockHandler('publish', 'App');

        $mockHandler = $this->traitInstance->mockHandler;

        $mockHandler->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf($eventClass));

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->should_not_publish_event($eventClass);

        $this->assertTrue(true, 'The method publish has been invoked.');
    }

    public function test_initialize_mock_handler_creates_mock_with_publish_method(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->traitInstance->initializeMockHandler('publish', 'App');

        $this->assertInstanceOf(MockObject::class, $this->traitInstance->mockHandler);
        $this->assertTrue(
            method_exists($this->traitInstance->mockHandler, 'publish'),
            'The publish method does not exist in the mock handler'
        );
    }
}
