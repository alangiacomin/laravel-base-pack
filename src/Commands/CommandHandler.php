<?php

namespace Alangiacomin\LaravelBasePack\Commands;

use Alangiacomin\LaravelBasePack\Bus\BusHandler;
use Alangiacomin\LaravelBasePack\Bus\IBusObject;
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
    // use HasBindingInjection;

    /**
     * Command constructor
     *
     * @param  IBusObject  $busObject The command
     */
    public function __construct(IBusObject $busObject)
    {
        $this->busObject = $busObject;
        // $this->injectProperties();
    }

    /**
     * Gets default response, overridable
     *
     * @return object|null Response after handler execution
     */
    public function getResponse(): ?object
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
        if (class_exists($ruleClass)) {
            $ruleInstance = (new $ruleClass($this->busObject));
            if ($ruleInstance instanceof CommandRule) {
                $ruleInstance->evaluate();
            }
        }
    }
}
