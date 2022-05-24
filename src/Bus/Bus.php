<?php

namespace Alangiacomin\LaravelBasePack\Bus;

use Alangiacomin\LaravelBasePack\Commands\ICommand;
use Alangiacomin\LaravelBasePack\Events\IEvent;
use Alangiacomin\LaravelBasePack\Services\ILoggerService;

final class Bus
{
    /**
     * Send and execute command synchronously
     *
     * @param  ICommand  $command
     * @param  ILoggerService  $logger
     * @return mixed
     */
    final public static function executeCommand(ICommand $command, ILoggerService $logger): mixed
    {
        $logger->sent($command);

        $handlerClass = config('basepack.namespaces.commandHandlers').'\\'.$command->handlerName();
        return (new $handlerClass($command))->handle($command);
    }

    /**
     * Send and execute command asynchronously
     *
     * @param  ICommand  $command
     * @param  ILoggerService  $logger
     */
    final public static function sendCommand(ICommand $command, ILoggerService $logger)
    {
        $logger->sent($command);

        $handlerClass = config('basepack.namespaces.commandHandlers').'\\'.$command->handlerName();
        call_user_func(array($handlerClass, 'dispatch'), $command);
    }

    /**
     * Send event which will be handled asynchronously
     *
     * @param  IEvent  $event
     * @param  ILoggerService  $logger
     * @return void
     */
    final public static function publishEvent(IEvent $event, ILoggerService $logger): void
    {
        $logger->sent($event);

        $classNameWithNamespace = $event->class();
        call_user_func(array($classNameWithNamespace, 'dispatch'), $event->props());
    }

    /**
     * Send event which will be handled synchronously
     *
     * @param  IEvent  $event
     * @param  ILoggerService  $logger
     * @return void
     */
    final public static function raiseEvent(IEvent $event, ILoggerService $logger): void
    {
        $logger->sent($event);

        $classNameWithNamespace = $event->class();
        (new $classNameWithNamespace())->handle($event);
    }
}
