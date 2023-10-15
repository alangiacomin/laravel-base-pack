<?php

namespace Alangiacomin\LaravelBasePack\Bus;

use Alangiacomin\LaravelBasePack\Commands\CommandHandler;
use Alangiacomin\LaravelBasePack\Exceptions\BusException;
use Alangiacomin\LaravelBasePack\Facades\LaravelBasePackFacade;
use Alangiacomin\LaravelBasePack\Traits\HasBindingInjection;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

abstract class BusHandler implements ShouldQueue
{
    use HasBindingInjection;
    use InteractsWithQueue;

    /**
     * {@see Command} or {@see Event} to handle
     */
    protected IBusObject $busObject;

    /**
     * Maximum retries if handling fails
     */
    protected int $maxRetries = 3;

    /**
     * Defines if handling is active, used by the retry algorithm
     */
    protected bool $isActive = true;

    /**
     * Sets handling definitely failed
     * Must be public because can be called from outside scope (by Laravel)
     */
    final public function failed(Throwable $ex): void
    {
        $this->fail($ex);
        $this->isActive = false;
    }

    /**
     * Execute the command or event body
     */
    abstract protected function execute();

    /**
     * Handle the job
     *
     * @throws Throwable
     */
    final protected function handleObject(IBusObject $busObject = null): void
    {
        $this->injectProps();

        if (isset($busObject)) {
            $this->busObject = $busObject;
        }

        $retry = 0;

        while ($this->isActive) {
            try {
                if (++$retry > $this->maxRetries) {
                    break;
                }

                $this->handleObjectExecution();
                $this->isActive = false;
            } catch (Throwable $ex) {
                LaravelBasePackFacade::callWithInjection($this, 'failed', ['ex' => $ex]);

                // if sync, re-throws exception
                if (!$this->isJob() || $this->job->getQueue() == 'sync') {
                    throw $ex;
                }

                // if async, handle the failure
                $this->notifyFailures($this->busObject, $ex);
            }
        }
    }

    /**
     * Entry point of object handling execution
     *
     * @throws Exception
     */
    final public function handleObjectExecution(): void
    {
        $this->setTypedObject();

        LaravelBasePackFacade::callWithInjection($this, 'execute');
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
                throw new BusException("'command' property must be defined");
            }

            $this->command = $this->busObject;
        } /* elseif ($this instanceof EventHandler) {
            if (! property_exists($this, 'event')) {
                throw new BusException("'event' property must be defined");
            }

            $this->event = $this->busObject;
        } */ else {
            throw new BusException("Handler must be a 'CommandHandler' or an 'EventHandler'");
        }
    }

    /**
     * Detects if handling a Laravel Job
     */
    final protected function isJob(): bool
    {
        return isset($this->job);
    }

    /**
     * Manage the failed handling.
     * Default keeps handling active, can be overridden.
     */
    protected function notifyFailures(/* IBusObject $busObject, Throwable $ex */): void
    {
        $this->isActive = true;
        if (isset($this->busObject)) {
            $this->busObject = $this->busObject->clone();
        }
    }
}
