<?php

namespace Alangiacomin\LaravelBasePack\Models;

use Alangiacomin\LaravelBasePack\Events\IEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Aggregate root
 */
abstract class Aggregate extends Model implements IAggregate
{
    use SoftDeletes;

    /**
     * @var IEvent[]
     */
    public array $events = [];

    /**
     * @inheritDoc
     */
    final public function raise(IEvent $event)
    {
        $this->events[] = $event;
    }
}
