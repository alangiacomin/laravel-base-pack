<?php

namespace Alangiacomin\LaravelBasePack\Commands;

use Alangiacomin\LaravelBasePack\Exceptions\BasePackException;

/**
 * @property ICommand $command
 */
abstract class CommandRule
{
    /**
     * @throws BasePackException
     */
    public function __construct(ICommand $command)
    {
        if (!property_exists($this, 'command')) {
            throw new BasePackException(get_class($this).": 'command' property must be defined");
        }

        $this->command = $command;
    }

    abstract public function evaluate(): void;
}
