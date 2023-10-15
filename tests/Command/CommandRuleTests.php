<?php

namespace Alangiacomin\LaravelBasePack\Tests\Command;

use Alangiacomin\LaravelBasePack\Commands\CommandRule;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandWithRuleExample;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandWithRuleExampleRule;
use Alangiacomin\LaravelBasePack\Tests\TestCase;

class CommandRuleTests extends TestCase
{
    public CommandRule $rule;

    /***************/
    /* CONSTRUCTOR */
    /***************/

    public function test_commandRule_constructor()
    {
        // Arrange
        $command = new CommandWithRuleExample();

        // Act
        $this->rule = new CommandWithRuleExampleRule($command);

        // Assert
        expect($this->rule->command)->toBe($command);
    }
}
