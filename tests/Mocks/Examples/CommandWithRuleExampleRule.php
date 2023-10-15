<?php

namespace Alangiacomin\LaravelBasePack\Tests\Mocks\Examples;

use Alangiacomin\LaravelBasePack\Commands\CommandRule;
use Exception;

class CommandWithRuleExampleRule extends CommandRule
{
    public CommandWithRuleExample $command;

    /**
     * @throws Exception
     */
    public function evaluate(): void
    {
        if (!$this->command->ruleOk) {
            throw new Exception('Rule is not respected');
        }
    }
}
