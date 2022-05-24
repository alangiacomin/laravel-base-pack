<?php /** @noinspection PhpUnused */

namespace Alangiacomin\LaravelBasePack\CommandHandlers;

use Alangiacomin\LaravelBasePack\Facades\LaravelBasePackFacade;
use Alangiacomin\LaravelBasePack\Models\IAggregate;
use Exception;
use Illuminate\Support\Facades\DB;
use ReflectionProperty;

/**
 * @property IAggregate $aggregate
 */
abstract class AggregateCommandHandler extends CommandHandler
{
    /**
     * Execute the command
     *
     * @return  void
     * @throws Exception
     */
    final public function execute(): void
    {
        if (!property_exists($this, "aggregate"))
        {
            throw new Exception("'aggregate' property must be defined");
        }

        $this->aggregate = $this->readAggregate();

        DB::transaction(function () {
            LaravelBasePackFacade::callWithInjection($this, 'apply');
            foreach ($this->aggregate->events as $event)
            {
                $this->aggregate->{'apply'.$event->name()}($event);
            }
        });
    }

    abstract public function apply(): void;

    /**
     * @throws Exception
     */
    private function readAggregate()
    {
        $rp = new ReflectionProperty(static::class, 'aggregate');
        $class = $rp->getType()->getName();
        $agg = call_user_func([$class, 'find'], $this->command->aggregateId);
        if (!isset($agg))
        {
            throw new Exception("Aggregate not found");
        }
        return $agg;
    }
}
