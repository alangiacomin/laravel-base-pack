<?php

namespace Alangiacomin\LaravelBasePack\Services;

use Alangiacomin\LaravelBasePack\Bus\IBusObject;
use Throwable;

interface ILoggerService extends IService
{
    /**
     * Log sent object
     *
     * @param  IBusObject  $obj
     * @return void
     */
    function sent(IBusObject $obj);

    /**
     * Log received object
     *
     * @param  IBusObject  $obj
     * @return void
     */
    function received(IBusObject $obj);

    /**
     * Log exception while handling object
     *
     * @param  IBusObject  $obj
     * @param  Throwable  $ex
     * @return void
     */
    function exception(IBusObject $obj, Throwable $ex);
}
