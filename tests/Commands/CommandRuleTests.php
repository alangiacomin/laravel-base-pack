<?php

namespace Alangiacomin\LaravelBasePack\Tests\Commands;

use Alangiacomin\LaravelBasePack\Commands\CommandRule;
use Alangiacomin\LaravelBasePack\Exceptions\BasePackException;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandExample;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandWithRuleExample;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandWithRuleExampleRule;
use Alangiacomin\LaravelBasePack\Tests\TestCase;

class CommandRuleTests extends TestCase
{
    public CommandRule $rule;

    /***************/
    /* CONSTRUCTOR */
    /***************/

    public function test_constructor_ok()
    {
        // Arrange
        $command = new CommandWithRuleExample();

        // Act
        $this->rule = new CommandWithRuleExampleRule($command);

        // Assert
        expect($this->rule->command)->toBe($command);
    }

    public function test_constructor_missing_prop()
    {
        // Arrange
        $command = new CommandExample();

        // Act
        $this->shouldThrowException(
            fn () => new CommandRuleTestable($command),
            BasePackException::class,
            "Alangiacomin\LaravelBasePack\Tests\Commands\CommandRuleTestable: 'command' property must be defined"
        );
    }
}
