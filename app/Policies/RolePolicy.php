<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        if (empty($permission = Permission::findWithModelAndAction('role', 'read'))) {
            return false;
        }
        return $user->hasPermission($permission);
    }

    public function view(User $user): bool
    {
        if (empty($permission = Permission::findWithModelAndAction('role', 'read'))) {
            return false;
        }
        return $user->hasPermission($permission);
    }

    public function create(User $user): bool
    {
        if (empty($permission = Permission::findWithModelAndAction('role', 'create'))) {
            return false;
        }
        return $user->hasPermission($permission);
    }

    public function update(User $user): bool
    {
        if (empty($permission = Permission::findWithModelAndAction('role', 'update'))) {
            return false;
        }
        return $user->hasPermission($permission);
    }

    public function delete(User $user): bool
    {
        if (empty($permission = Permission::findWithModelAndAction('role', 'delete'))) {
            return false;
        }
        return $user->hasPermission($permission);
    }

    public function restore(User $user, Role $role): bool
    {
        return false;
    }

    public function forceDelete(User $user, Role $role): bool
    {
        return false;
    }
}
