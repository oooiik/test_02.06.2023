<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
    ];

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class);
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
        return (bool) $role->intersect($this->roles)->count();
    }

}
