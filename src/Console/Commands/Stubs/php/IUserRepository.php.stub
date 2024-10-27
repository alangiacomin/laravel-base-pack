<?php

namespace App\Repositories;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Collection;

interface IUserRepository
{
    public function findByEmail(string $email): ?User;

    public function getAll(): Collection;
}
