<?php

namespace Alangiacomin\LaravelBasePack\EventHandlers;

use Alangiacomin\LaravelBasePack\Bus\Bus;
use Alangiacomin\LaravelBasePack\Bus\BusHandler;
use Alangiacomin\LaravelBasePack\Bus\IBusObject;
use Alangiacomin\LaravelBasePack\Commands\ICommand;
use Alangiacomin\LaravelBasePack\Events\IEvent;
use Alangiacomin\LaravelBasePack\Facades\LaravelBasePackFacade;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

/**
 * @property IBusObject $event
 */
abstract class EventHandler extends BusHandler implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the {@see Event}
     *
     * @param  IEvent  $event
     * @return void
     * @throws Throwable
     */
    final public function handle(IEvent $event): void
    {
        $this->handleObject($event);
    }

    /**
     * Push command on the bus
     *
     * @param  ICommand  $command
     * @return void
     */
    final public function sendCommand(ICommand $command): void
    {
        LaravelBasePackFacade::callStaticWithInjection(
            Bus::class,
            'sendCommand',
            ['command' => $command]);
    }

    /**
     * Defines if the event job must be queued or ignored
     *
     * @param  IEvent  $event
     * @return bool
     */
    final public function shouldQueue(IEvent $event): bool
    {
        $classNameWithNamespace = get_class($this);
        $className = substr($classNameWithNamespace, strrpos($classNameWithNamespace, "\\") + 1);
        $handledEventName = str_replace("Handler", "", $className);

        return $event->name() == $handledEventName;
    }
}
