<?php

namespace AlanGiacomin\LaravelBasePack\QueueObject;

use AlanGiacomin\LaravelBasePack\Commands\CommandHandler;
use AlanGiacomin\LaravelBasePack\Commands\Contracts\ICommand;
use AlanGiacomin\LaravelBasePack\Events\Contracts\IEvent;
use AlanGiacomin\LaravelBasePack\Events\EventHandler;
use AlanGiacomin\LaravelBasePack\Exceptions\BasePackException;
use AlanGiacomin\LaravelBasePack\QueueObject\Contracts\IMessageBus;
use AlanGiacomin\LaravelBasePack\QueueObject\Contracts\IQueueObject;
use Alangiacomin\LaravelBasePack\Traits\HasBindingInjection;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

abstract class QueueObjectHandler implements ShouldQueue
{
    use HasBindingInjection;
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

    public function __construct(
        protected IMessageBus $messageBus
    ) {
        $this->injectProps();
    }

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
     * @throws Exception
     */
    final protected function handleObject(): void
    {
        $isJob = isset($this->job);
        $queue = $isJob ? $this->job->getQueue() : null;
        $isSync = !$isJob || $queue == 'sync';

        $this->setTypedObject();

        if ($isSync) {
            $this->tries = 1;
            try {
                $this->execute();
            } catch (Throwable $e) {
                $this->failed($e);
            }
        } else {
            $this->execute();
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
