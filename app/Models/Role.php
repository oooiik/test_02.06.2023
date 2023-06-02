<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function givePermissionTo(string|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('title', $permission)->firstOrFail();
        }
        $this->permissions()->attach($permission);
    }

    public function removePermissionTo(string|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('title', $permission)->firstOrFail();
        }
        $this->permissions()->detach($permission);
    }

    /**
     * @param Collection|Permission[] $permissions
     * @return void
     */
    public function syncPermissions(array|Collection $permissions): void
    {
        $this->permissions()->sync($permissions);
    }

    public function hasPermission(string|Permission $permission): bool
    {
        if (is_string($permission)) {
            return $this->permissions()->where('title', $permission)->exists();
        }
        return (bool)$permission->intersect($this->permissions)->count();
    }

}
