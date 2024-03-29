<?php

namespace Alangiacomin\LaravelBasePack\Events;

use Alangiacomin\LaravelBasePack\Bus\BusObject;
use Alangiacomin\LaravelBasePack\Core\NamespaceUtility;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class Event extends BusObject implements IEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The event's broadcast name
     */
    public function broadcastAs(): string
    {
        return NamespaceUtility::elementName(get_class($this));
    }
}
