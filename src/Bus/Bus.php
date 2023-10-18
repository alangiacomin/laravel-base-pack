<?php

namespace Alangiacomin\LaravelBasePack\Bus;

use Alangiacomin\LaravelBasePack\Commands\ICommand;
use Alangiacomin\LaravelBasePack\Events\IEvent;

final class Bus
{
    /**
     * Send and execute command synchronously
     *
     * @param  ICommand  $command Command
     *
     * @noinspection PhpUnused
     */
    final public static function executeCommand(ICommand $command): mixed
    {
        $handlerClass = self::commandHandlerClassName($command);

        return (new $handlerClass($command))->handle($command);
    }

    /**
     * Send and execute command asynchronously
     *
     * @param  ICommand  $command Command
     *
     * @noinspection PhpUnused
     */
    final public static function sendCommand(ICommand $command): void
    {
        $handlerClass = self::commandHandlerClassName($command);
        call_user_func([$handlerClass, 'dispatch'], $command);
    }

    /**
     * Send event which will be handled asynchronously
     *
     * @param  IEvent  $event Event
     */
    final public static function publishEvent(IEvent $event): void
    {
        $classNameWithNamespace = $event->fullName();
        call_user_func([$classNameWithNamespace, 'dispatch'], $event->props());
    }

    /**
     * Detects command handler class
     *
     * @param  ICommand  $command Command
     * @return string Class name
     */
    private static function commandHandlerClassName(ICommand $command): string
    {
        return $command->fullName().'Handler';
    }
}
