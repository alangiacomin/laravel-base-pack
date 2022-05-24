<?php

namespace Alangiacomin\LaravelBasePack\Traits;

use Illuminate\Contracts\Auth\Authenticatable;

trait HasExtendedPermissions
{
    public static function withPermissions(?Authenticatable $authUser)
    {
        $user = null;
        if ($authUser != null)
        {
            $user = clone $authUser;
            $authUser->getRoleNames();
            $user->groups = $authUser->getRoleNames()->map(function ($x) {
                return $x;
            });
            $user->perms = $authUser->getPermissionNames()->map(function ($x) {
                return $x;
            });
        }
        return $user;
    }
}
