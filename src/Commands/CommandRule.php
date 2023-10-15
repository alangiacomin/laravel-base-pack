<?php

namespace Alangiacomin\LaravelBasePack\Commands;

/**
 * @property ICommand $command
 */
abstract class CommandRule
{
    public function __construct(ICommand $command)
    {
        if (!property_exists($this, 'command')) {
            exit($command->fullName().": 'command' property must be defined");
        }

        $this->command = $command;
    }

    abstract public function evaluate(): void;
}
