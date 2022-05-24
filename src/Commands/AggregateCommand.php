<?php /** @noinspection PhpUnused */

namespace Alangiacomin\LaravelBasePack\Commands;

abstract class AggregateCommand extends Command
{
    /**
     * Aggregate ID
     *
     * @var mixed
     */
    public mixed $aggregateId;
}
