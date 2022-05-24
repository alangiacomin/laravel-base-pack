<?php

namespace Alangiacomin\LaravelBasePack\Repositories;

use Alangiacomin\LaravelBasePack\Models\IAggregate;

abstract class Repository implements IRepository
{
    final public function paramsToModel(IAggregate $aggregate, ...$params)
    {
        foreach ($params as $key => $value)
        {
            $aggregate->$key = $value;
        }
    }
}
