<?php

namespace AlanGiacomin\LaravelBasePack\Commands;

use AlanGiacomin\LaravelBasePack\Commands\Contracts\ICommand;
use AlanGiacomin\LaravelBasePack\QueueObject\QueueObjectHandler;
use Exception;
use Throwable;

abstract class CommandHandler extends QueueObjectHandler
{
    public CommandResult $result;

    /**
     * Execute the job.
     *
     * @throws Exception
     */
    final public function handle(ICommand $command): CommandResult
    {
        $this->queueObject = $command;
        $this->result = new CommandResult();

        $this->handleObject();

        if ($this->result->success) {
            $this->result->setSuccess($this->getResponse());
        }

        return $this->result;
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

    final protected function failed(Throwable $exception): void
    {
        parent::failed($exception);
        $this->result->setFailure($exception->getMessage());
    }
}
