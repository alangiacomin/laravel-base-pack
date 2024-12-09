<?php

namespace AlanGiacomin\LaravelBasePack\Events;

use AlanGiacomin\LaravelBasePack\Events\Contracts\IEvent;
use AlanGiacomin\LaravelBasePack\Exceptions\BasePackException;
use AlanGiacomin\LaravelBasePack\QueueObject\QueueObjectHandler;
use Illuminate\Support\Facades\Auth;
use Throwable;

abstract class EventHandler extends QueueObjectHandler
{
    /**
     * Execute the job.
     *
     * @throws BasePackException
     * @throws Throwable
     */
    final public function handle(IEvent $event): void
    {
        Auth::loginUsingId($event->userId);
        $this->queueObject = $event;
        $this->setTypedObject();
        $this->handleObject();
        $notificationName = $event->fullName().'Notification';
        /** @noinspection PhpUndefinedMethodInspection */
        Auth::user()->notify(new $notificationName($event));
    }

    final protected function failed(Throwable $exception): void
    {
        parent::failed($exception);
    }

    /**
     * Sets generic bus object as the specific typed object managed by the handler
     *
     * @throws BasePackException
     */
    private function setTypedObject(): void
    {
        if (!property_exists($this, 'event')) {
            throw new BasePackException($this->queueObject->fullName().": 'event' property must be defined");
        }

        $this->event = $this->queueObject;
    }
}
