<?php

namespace Alangiacomin\LaravelBasePack\Services;

use Alangiacomin\LaravelBasePack\Bus\IBusObject;
use Throwable;

class EmptyLoggerService implements ILoggerService
{
    public function sent(IBusObject $obj)
    {
    }

    public function received(IBusObject $obj)
    {
    }

    public function exception(IBusObject $obj, Throwable $ex)
    {
    }
}
