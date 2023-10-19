<?php

namespace Alangiacomin\LaravelBasePack\Commands;

use Alangiacomin\LaravelBasePack\Bus\Bus;
use Alangiacomin\LaravelBasePack\Bus\BusHandler;
use Alangiacomin\LaravelBasePack\Bus\IBusObject;
use Alangiacomin\LaravelBasePack\Events\IEvent;
use Alangiacomin\LaravelBasePack\Facades\LaravelBasePackFacade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Throwable;

/**
 * @property IBusObject $command
 */
abstract class CommandHandler extends BusHandler implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    /**
     * Command constructor
     *
     * @param  IBusObject  $busObject The command
     */
    public function __construct(IBusObject $busObject)
    {
        $this->busObject = $busObject;
    }

    /**
     * Gets default response, overridable
     *
     * @return object|string|null Response after handler execution
     */
    public function getResponse(): object|string|null
    {
        return null;
    }

    /**
     * Handle the {@see Command}
     *
     * @return CommandResult Command result
     */
    final public function handle(): CommandResult
    {
        $commandResult = new CommandResult();

        try {
            $this->evaluateRule();

            $this->handleObject($this->busObject);

            if (!isset($this->job) || $this->job->getQueue() == 'sync') {
                $response = LaravelBasePackFacade::callWithInjection($this, 'getResponse');
                $commandResult->setSuccess($response);
            }
        } catch (Throwable $ex) {
            $commandResult->setFailure($ex->getMessage());
        }

        return $commandResult;
    }

    /**
     * Evaluates the rule before executing the command
     */
    protected function evaluateRule(): void
    {
        $ruleClass = $this->busObject->fullName().'Rule';

        try {
            $ruleInstance = new $ruleClass($this->busObject);
        } catch (Throwable) {
            return;
        }

        if ($ruleInstance instanceof CommandRule) {
            $ruleInstance->evaluate();
        }
    }

    /**
     * Publish event on the bus
     */
    final public function publish(IEvent $event): void
    {
        LaravelBasePackFacade::callStaticWithInjection(
            Bus::class,
            'publishEvent',
            ['event' => $event]
        );
    }
}
