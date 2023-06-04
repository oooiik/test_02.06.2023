<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission(Permission::findWithModelAndAction('user', 'read'));
    }

    public function view(User $user, int $user_id): bool
    {
        if ($user->hasPermission(Permission::findWithModelAndAction('user', 'read'))) return true;
        if (empty($model = User::find($user_id))) return false;
        return $user->hasPermission(Permission::findWithModelAndAction('user', 'read-my')) && $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission(Permission::findWithModelAndAction('user', 'create'));
    }

    public function update(User $user, int $user_id): bool
    {
        if ($user->hasPermission(Permission::findWithModelAndAction('user', 'update'))) return true;
        if (empty($model = User::find($user_id))) return false;
        return $user->hasPermission(Permission::findWithModelAndAction('user', 'update-my')) && $user->id === $model->id;
    }

    public function delete(User $user, int $user_id): bool
    {
        if ($user->hasPermission(Permission::findWithModelAndAction('user', 'delete'))) return true;
        if (empty($model = User::find($user_id))) return false;
        return $user->hasPermission(Permission::findWithModelAndAction('user', 'delete-my')) && $user->id === $model->id;
    }

    public function restore(User $user, User $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
