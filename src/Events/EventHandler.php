<?php

namespace Alangiacomin\LaravelBasePack\Events;

use Alangiacomin\LaravelBasePack\Bus\Bus;
use Alangiacomin\LaravelBasePack\Bus\BusHandler;
use Alangiacomin\LaravelBasePack\Bus\IBusObject;
use Alangiacomin\LaravelBasePack\Commands\ICommand;
use Alangiacomin\LaravelBasePack\Core\NamespaceUtility;
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
     * @throws Throwable
     */
    final public function handle(IEvent $event): void
    {
        $this->handleObject($event);
    }

    /**
     * Push command on the bus
     */
    final public function send(ICommand $command): void
    {
        LaravelBasePackFacade::callStaticWithInjection(
            Bus::class,
            'sendCommand',
            ['command' => $command]
        );
    }

    /**
     * Defines if the event job must be queued or ignored
     */
    final public function shouldQueue(IEvent $event): bool
    {
        $handlerNameWithNamespace = get_class($this);
        $className = NamespaceUtility::elementName($handlerNameWithNamespace);
        $handledEventName = str_replace('Handler', '', $className);

        return NamespaceUtility::elementName(get_class($event)) == $handledEventName;
    }
}
