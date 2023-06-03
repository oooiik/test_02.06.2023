<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends \Illuminate\Database\Eloquent\Model<\App\Models\Permission>
 * @property int $id
 * @property string $model
 * @property string $action
 */
class Permission extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'model',
        'action'
    ];

    protected $casts = [
        'id' => 'integer',
        'model' => 'string',
        'action' => 'string',
    ];

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id');
    }

    public function giveRoleTo(string|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::where('title', $role)->firstOrFail();
        }
        $this->roles()->attach($role);
    }

    public function removeRoleTo(string|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::where('title', $role)->firstOrFail();
        }
        $this->roles()->detach($role);
    }

    public function syncRoles(string|Role $roles): void
    {
        $roles = collect($roles)->flatten()->map(function ($role) {
            if (is_string($role)) {
                return Role::where('title', $role)->firstOrFail();
            }
            return $role;
        })->all();
        $this->roles()->sync($roles);
    }

    public function hasRole(string|Role $role): bool
    {
        if (is_string($role)) {
            return $this->roles()->where('title', $role)->exists();
        }
        return (bool)$role->intersect($this->roles)->count();
    }

}
