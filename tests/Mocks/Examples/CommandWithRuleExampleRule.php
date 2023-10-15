<?php

namespace Alangiacomin\LaravelBasePack\Tests\Mocks\Examples;

use Exception;

class CommandWithRuleExampleRule
{
    /**
     * @throws Exception
     */
    public function evaluate(CommandWithRuleExample $command): void
    {
        if (!$command->ruleOk) {
            throw new Exception('Rule is not respected');
        }
    }
}
