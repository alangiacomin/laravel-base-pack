<?php

namespace AlanGiacomin\LaravelBasePack\Events;

use AlanGiacomin\LaravelBasePack\Events\Contracts\IEvent;
use AlanGiacomin\LaravelBasePack\QueueObject\QueueObjectHandler;
use Exception;
use Throwable;

abstract class EventHandler extends QueueObjectHandler
{
    /**
     * Execute the job.
     *
     * @throws Exception
     */
    final public function handle(IEvent $event): void
    {
        $this->queueObject = $event;

        $this->handleObject();
    }

    final protected function failed(Throwable $exception): void
    {
        parent::failed($exception);
    }
}
