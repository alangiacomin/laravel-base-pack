<?php

namespace Alangiacomin\LaravelBasePack\Tests\Commands;

use Alangiacomin\LaravelBasePack\Bus\Bus;
use Alangiacomin\LaravelBasePack\Commands\CommandHandler;
use Alangiacomin\LaravelBasePack\Commands\CommandResult;
use Alangiacomin\LaravelBasePack\Facades\LaravelBasePackFacade;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandExample;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandExampleHandler;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandWithExceptionExample;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandWithExceptionExampleHandler;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandWithRuleExample;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandWithRuleExampleHandler;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\EventExample;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\JobExample;
use Alangiacomin\LaravelBasePack\Tests\Mocks\LaravelBasePackFacadeMock;
use Alangiacomin\LaravelBasePack\Tests\TestCase;

class CommandHandlerTests extends TestCase
{
    public CommandHandler $handler;

    /***************/
    /* CONSTRUCTOR */
    /***************/

    public function test_constructor()
    {
        // Arrange
        $command = new CommandExample();

        // Act
        $this->handler = new CommandExampleHandler($command);

        // Assert
        expect($this->handler->busObject)->toBe($command);
    }

    /****************/
    /* GET RESPONSE */
    /****************/

    public function test_getResponse()
    {
        // Arrange
        $command = new CommandExample();
        $this->handler = new CommandExampleHandler($command);

        // Act
        $ret = $this->handler->getResponse();

        // Assert
        expect($ret)->toBeNull();
    }

    /**********/
    /* HANDLE */
    /**********/

    public function test_handle_return_type()
    {
        // Arrange
        $command = new CommandExample();
        $this->handler = new CommandExampleHandler($command);

        // Act
        $ret = $this->handler->handle();

        // Assert
        expect($ret)->toBeInstanceOf(CommandResult::class);
    }

    public function test_handle_rule_missing()
    {
        // Arrange
        $command = new CommandExample();
        $this->handler = new CommandExampleHandler($command);

        // Act
        $ret = $this->handler->handle();

        // Assert
        expect($ret->success)->toBeTrue();
    }

    public function test_handle_rule_ok()
    {
        // Arrange
        $command = new CommandWithRuleExample();
        $command->ruleOk = true;
        $this->handler = new CommandWithRuleExampleHandler($command);

        // Act
        $ret = $this->handler->handle();

        // Assert
        expect($ret->success)->toBeTrue();
        expect($ret->errors)->toBeEmpty();
    }

    public function test_handle_rule_fail()
    {
        // Arrange
        $command = new CommandWithRuleExample();
        $command->ruleOk = false;
        $this->handler = new CommandWithRuleExampleHandler($command);

        // Act
        $ret = $this->handler->handle();

        // Assert
        expect($ret->success)->toBeFalse();
        expect($ret->errors)->toContain('Rule is not respected');
    }

    public function test_handle_exception()
    {
        // Arrange
        $command = new CommandWithExceptionExample();
        $this->handler = new CommandWithExceptionExampleHandler($command);

        // Act
        $ret = $this->handler->handle();

        // Assert
        expect($ret->success)->toBeFalse();
        expect($ret->errors)->toBe(['This command throws exception']);
    }

    public function test_handle_not_job()
    {
        // Arrange
        $command = new CommandExample();
        $this->handler = new CommandExampleHandler($command);
        $this->handler->job = null;

        // Act
        new LaravelBasePackFacadeMock();
        LaravelBasePackFacade::shouldReceive('callWithInjection')
            ->once()->withSomeOfArgs('getResponse');
        $ret = $this->handler->handle();

        // Assert
        expect($ret->errors)->toBe([]);
        expect($ret->success)->toBeTrue();
    }

    public function test_handle_job_sync()
    {
        // Arrange
        $command = new CommandExample();
        $this->handler = new CommandExampleHandler($command);
        $this->handler->job = new JobExample('sync');

        // Act
        new LaravelBasePackFacadeMock();
        LaravelBasePackFacade::shouldReceive('callWithInjection')
            ->once()->withSomeOfArgs('getResponse');
        $ret = $this->handler->handle();

        // Assert
        expect($ret->success)->toBeTrue();
    }

    public function test_handle_job_other()
    {
        // Arrange
        $command = new CommandExample();
        $this->handler = new CommandExampleHandler($command);
        $this->handler->job = new JobExample('otherQueue');

        // Act
        new LaravelBasePackFacadeMock();
        LaravelBasePackFacade::shouldReceive('callWithInjection')
            ->never()->withSomeOfArgs('getResponse');
        $ret = $this->handler->handle();

        // Assert
        expect($ret->success)->toBeTrue();
    }

    /***********/
    /* PUBLISH */
    /***********/

    public function test_publish()
    {
        $event = new EventExample();
        $command = new CommandExample();
        $this->handler = new CommandExampleHandler($command);

        LaravelBasePackFacade::shouldReceive('callStaticWithInjection')->with(
            Bus::class,
            'publishEvent',
            ['event' => $event]
        )->once();

        $this->handler->publish($event);
    }
}
