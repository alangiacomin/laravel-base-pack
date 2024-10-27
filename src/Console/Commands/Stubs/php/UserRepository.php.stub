<?php

namespace App\Repositories;

use AlanGiacomin\LaravelBasePack\Repositories\Repository;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Collection;

final class UserRepository extends Repository implements IUserRepository
{
    public function findByEmail(string $email): ?User
    {
        $user = User::where('email', $email)->first();

        return $user ?? null;
    }

    public function getAll(): Collection
    {
        return User::all();
    }
}
