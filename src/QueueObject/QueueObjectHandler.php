<?php

namespace AlanGiacomin\LaravelBasePack\QueueObject;

use AlanGiacomin\LaravelBasePack\Commands\Command;
use AlanGiacomin\LaravelBasePack\Commands\CommandHandler;
use AlanGiacomin\LaravelBasePack\Commands\Contracts\ICommand;
use AlanGiacomin\LaravelBasePack\Events\Contracts\IEvent;
use AlanGiacomin\LaravelBasePack\Events\Event;
use AlanGiacomin\LaravelBasePack\Events\EventHandler;
use AlanGiacomin\LaravelBasePack\Exceptions\BasePackException;
use AlanGiacomin\LaravelBasePack\QueueObject\Contracts\IMessageBus;
use AlanGiacomin\LaravelBasePack\QueueObject\Contracts\IQueueObject;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Throwable;

abstract class QueueObjectHandler implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * The number of times the queued listener may be attempted.
     */
    public int $tries = 3;

    /**
     * {@see Command} or {@see Event} to handle
     */
    protected IQueueObject $queueObject;

    /**
     * Message bus where {@see Command} and {@see Event} are dispatched
     */
    protected IMessageBus $messageBus;

    /**
     * Execute the command or event body
     */
    abstract protected function execute();

    /**
     * Calculate the number of seconds to wait before retrying the job.
     */
    public function backoff(): array|int
    {
        return [1, 1, 5, 1, 1, 5, 1, 1];
    }

    protected function manageFailed(Throwable $exception): void {}

    /**
     * @throws Throwable
     */
    final protected function handleObject(): void
    {
        $this->messageBus = app(IMessageBus::class);

        $isJob = isset($this->job);
        $queue = $isJob ? $this->job->getQueue() : null;
        $isSync = !$isJob || $queue == 'sync';

        $this->setTypedObject();

        if ($isSync) {
            $this->tries = 1;
            try {
                $this->executeWithinTransaction();
            } catch (Throwable $e) {
                $this->failed($e);
            }
        } else {
            $this->executeWithinTransaction();
        }
    }

    /**
     * Safe execution within a transaction
     */
    final protected function executeWithinTransaction(): void
    {
        try {
            DB::beginTransaction();
            $this->execute();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function failed(Throwable $exception): void
    {
        $this->manageFailed($exception);
    }

    final protected function publish(IEvent $event): void
    {
        $this->messageBus->publish($event);
    }

    final protected function send(ICommand $command): void
    {
        $this->messageBus->dispatch($command);
    }

    /**
     * Sets generic bus object as the specific typed object managed by the handler
     *
     * @throws Exception
     */
    private function setTypedObject(): void
    {
        if ($this instanceof CommandHandler) {
            if (!property_exists($this, 'command')) {
                throw new BasePackException($this->queueObject->fullName().": 'command' property must be defined");
            }

            $this->command = $this->queueObject;
        } elseif ($this instanceof EventHandler) {
            if (!property_exists($this, 'event')) {
                throw new BasePackException($this->queueObject->fullName().": 'event' property must be defined");
            }

            $this->event = $this->queueObject;
        } else {
            throw new BasePackException(get_class($this).": Handler must be a 'CommandHandler' or an 'EventHandler'");
        }
    }
}
