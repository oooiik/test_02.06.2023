<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\Statement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StatementPolicy
{
    public function viewAny(User $user): bool
    {
        $permissionRead = Permission::findWithModelAndAction('statement', 'read');
        $permissionReadMy = Permission::findWithModelAndAction('statement', 'read-my');
        return $user->hasPermission($permissionRead) || $user->hasPermission($permissionReadMy);
    }

    public function view(User $user, $statement_id): bool
    {
        $permissionRead = Permission::findWithModelAndAction('statement', 'read');
        if ($user->hasPermission($permissionRead)) {
            return true;
        }
        $permissionReadMy = Permission::findWithModelAndAction('statement', 'read-my');
        if (!$user->hasPermission($permissionReadMy)) {
            return false;
        }
        return Statement::query()->where('id', $statement_id)->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        $permissionCreate = Permission::findWithModelAndAction('statement', 'create');
        $permissionCreateMy = Permission::findWithModelAndAction('statement', 'create-my');
        return $user->hasPermission($permissionCreate) || $user->hasPermission($permissionCreateMy);
    }

    public function update(User $user, $statement_id): bool
    {
        $permissionRead = Permission::findWithModelAndAction('statement', 'update');
        if ($user->hasPermission($permissionRead)) {
            return true;
        }
        $permissionReadMy = Permission::findWithModelAndAction('statement', 'update-my');
        if (!$user->hasPermission($permissionReadMy)) {
            return false;
        }
        return Statement::query()->where('id', $statement_id)->where('user_id', $user->id)->exists();
    }

    public function delete(User $user, $statement_id): bool
    {
        $permissionRead = Permission::findWithModelAndAction('statement', 'delete');
        if ($user->hasPermission($permissionRead)) {
            return true;
        }
        $permissionReadMy = Permission::findWithModelAndAction('statement', 'delete-my');
        if (!$user->hasPermission($permissionReadMy)) {
            return false;
        }
        return Statement::query()->where('id', $statement_id)->where('user_id', $user->id)->exists();
    }

    public function restore(User $user, Statement $statement): bool
    {
        return false;
    }

    public function forceDelete(User $user, Statement $statement): bool
    {
        return false;
    }
}
