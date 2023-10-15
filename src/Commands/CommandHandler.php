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
     */
    public function __construct(IBusObject $busObject)
    {
        $this->busObject = $busObject;
        // $this->injectProperties();
    }

    /**
     * Gets default response, overridable
     *
     * @return null
     */
    public function getResponse()
    {
        return null;
    }

    /**
     * Handle the {@see Command}
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

    protected function evaluateRule(): void
    {
        $ruleClass = $this->busObject->fullName().'Rule';
        if (class_exists($ruleClass)) {
            (new $ruleClass())->evaluate($this->busObject);
        }
    }
}
