<?php

/** @noinspection PhpUndefinedMethodInspection */

namespace AlanGiacomin\LaravelBasePack\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use ReflectionClass;
use ReflectionException;

trait ControllerTestTrait
{
    public MockObject $mockController;

    public function should_execute_command(string $commandClass, InvokedCount $invokedCount): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->initializeMockController();
        $this->mockController->expects($invokedCount)
            ->method('executeCommand')
            ->with(
                $this->isInstanceOf($commandClass)
            );
    }

    /**
     * @throws ReflectionException
     */
    public function initializeMockController(): void
    {
        $calledClass = get_called_class();
        $controllerName = preg_replace('/Test$/', '', class_basename($calledClass));
        $namespace = (new ReflectionClass($calledClass))->getNamespaceName();
        $namespace = str_replace('Tests\Unit', 'App\Http', $namespace);
        $fullControllerClass = $namespace.'\\'.$controllerName;

        $mockBuilder = $this->getMockBuilder($fullControllerClass);

        $this->mockController = $mockBuilder
            ->onlyMethods(['executeCommand'])
            ->getMock();
    }
}
