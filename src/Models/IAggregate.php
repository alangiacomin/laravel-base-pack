<?php

namespace Alangiacomin\LaravelBasePack\Models;

use Alangiacomin\LaravelBasePack\Events\IEvent;

interface IAggregate
{
    /**
     * Raise aggregate event
     *
     * @param  IEvent  $event
     * @return void
     */
    function raise(IEvent $event);
}
