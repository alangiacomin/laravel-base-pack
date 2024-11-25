<?php

namespace App\Models\User\Contracts;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Collection;

interface IUserRepository
{
    public function findByEmail(string $email): ?User;

    public function findById(int $id): ?User;

    public function getAll(): Collection;
}
