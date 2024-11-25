<?php

namespace AlanGiacomin\LaravelBasePack\Repositories;

abstract class Repository implements IRepository
{
    protected string $model;

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->model, $name], $arguments);
    }

    /**
     * Returns default element from repository
     */
    protected function default(bool $allowNull = true): ?static
    {
        return $allowNull ? null : new static();
    }
}
