<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function setPasswordAttribute($password): void
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function hasRole($role): bool
    {
        return $this->roles()->where('title', $role)->exists();
    }

    public function assignRole(string|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::where('title', $role)->firstOrFail();
        }
        $this->roles()->attach($role);
    }

    public function removeRole(string|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::where('title', $role)->firstOrFail();
        }
        $this->roles()->detach($role);
    }

    public function syncRoles(string|Role $roles): void
    {
        $this->roles()->sync($roles);
    }

    public function hasPermission(string|Permission $permission): bool
    {
        if (is_string($permission)) {
            return $this->roles()
                ->has('permission', '>=', 1)
                ->exists();
        }
        return $this->roles()
            ->has('permission', '=', $permission->id)
            ->exists();
    }


}
