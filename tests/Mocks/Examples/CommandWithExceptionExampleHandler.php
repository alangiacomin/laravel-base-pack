<?php

namespace Alangiacomin\LaravelBasePack\Tests\Mocks\Examples;

use Alangiacomin\LaravelBasePack\Tests\Command\CommandHandlerTestable;
use Exception;

class CommandWithExceptionExampleHandler extends CommandHandlerTestable
{
    public CommandWithExceptionExample $command;

    /**
     * @throws Exception
     */
    public function execute()
    {
        throw new Exception('This command throws exception');
    }
}
