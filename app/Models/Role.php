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
        return $this->belongsToMany(
            Permission::class,
            'role_permission',
            'role_id',
            'permission_id',
            'id',
            'id'
        );
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
        $this->permissions()->detach();
        $this->permissions()->sync($permissions);
    }

    public function hasPermission(int|Permission $permission): bool
    {
        if (is_int($permission)) {
            return $this->permissions()->where('permissions.id', $permission)->exists();
        }
        return $this->permissions()->where('permissions.id', $permission->id)->exists();
    }

}
