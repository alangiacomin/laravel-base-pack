<?php

/** @noinspection PhpUndefinedMethodInspection */

namespace AlanGiacomin\LaravelBasePack\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use ReflectionException;

use function PHPUnit\Framework\exactly;

trait HandlerTestTrait
{
    public MockObject $mockHandler;

    public function should_execute_command(string $commandClass, int $times = 1): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->initializeMockHandler('executeCommand', 'App\Http');
        $this->mockHandler->expects($this->exactly($times))
            ->method('executeCommand')
            ->with(
                $this->isInstanceOf($commandClass)
            );
    }

    public function should_not_execute_command(string $commandClass): void
    {
        $this->should_execute_command($commandClass, 0);
    }

    public function should_publish_event(string $eventClass, int $times = 1): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->initializeMockHandler('publish', 'App');
        $this->mockHandler->expects($this->exactly($times))
            ->method('publish')
            ->with(
                $this->isInstanceOf($eventClass)
            );
    }

    public function should_not_publish_event(string $eventClass): void
    {
        $this->should_publish_event($eventClass, 0);
    }

    public function should_send_command(string $commandClass, int $times = 1): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->initializeMockHandler('send', 'App');
        $this->mockHandler->expects(exactly($times))
            ->method('send')
            ->with(
                $this->isInstanceOf($commandClass)
            );
    }

    public function should_not_send_command(string $commandClass): void
    {
        $this->should_send_command($commandClass, 0);
    }

    /**
     * Inizializza un mock per il controller o handler.
     *
     * @param  string  $method  Il nome del metodo da mockare (ad esempio 'executeCommand' o 'publish')
     * @param  string  $namespaceReplace  Il pattern per sostituire il namespace (es. 'Tests\Unit' con 'App\Http' o 'App')
     *
     * @throws ReflectionException
     */
    public function initializeMockHandler(string $method, string $namespaceReplace): void
    {
        $calledClass = get_called_class();
        $handlerName = preg_replace('/Test$/', '', class_basename($calledClass));
        $namespace = (new ReflectionClass($calledClass))->getNamespaceName();

        // Sostituisci il namespace come richiesto
        $namespace = str_replace('Tests\Unit', $namespaceReplace, $namespace);
        $fullHandlerClass = $namespace.'\\'.$handlerName;

        $mockBuilder = $this->getMockBuilder($fullHandlerClass)
            ->disableOriginalConstructor();

        $this->mockHandler = $mockBuilder
            ->onlyMethods([$method])
            ->getMock();
    }
}
