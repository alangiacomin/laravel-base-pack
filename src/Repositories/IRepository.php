<?php

namespace AlanGiacomin\LaravelBasePack\Repositories;

use AlanGiacomin\LaravelBasePack\Models\Contracts\IModel;

interface IRepository
{
    public function create(array $props): IModel;

    public function findById(int $id): ?IModel;
}
