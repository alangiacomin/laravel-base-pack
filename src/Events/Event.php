<?php

namespace Alangiacomin\LaravelBasePack\Events;

use Alangiacomin\LaravelBasePack\Bus\BusObject;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use JetBrains\PhpStorm\Pure;

abstract class Event extends BusObject implements IEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The event's broadcast name
     *
     * @return string
     */
    #[Pure] public function broadcastAs(): string
    {
        return $this->name();
    }
}
