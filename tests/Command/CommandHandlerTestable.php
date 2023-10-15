<?php

namespace Alangiacomin\LaravelBasePack\Tests\Command;

use Alangiacomin\LaravelBasePack\Commands\CommandHandler;
use Alangiacomin\LaravelBasePack\Tests\TestableCallables;
use Alangiacomin\LaravelBasePack\Tests\TestableModifiers;

abstract class CommandHandlerTestable extends CommandHandler
{
    use TestableCallables, TestableModifiers;

    // public $command;

    // public function __construct(IBusObject $busObject)
    // {
    //     parent::__construct($busObject);
    // }
}
