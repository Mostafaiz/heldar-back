<?php

namespace App\Models;

use App\Enums\UserRoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'name',
        'family',
        'email',
        'status',
        'address',
        'role',
        'level',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'status' => 'bool',
            'role' => UserRoleEnum::class
        ];
    }

    public function isManager()
    {
        return $this->role === UserRoleEnum::MANAGER;
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function level()
    {
        return $this->level;
    }

    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('name', $permission)->exists();
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function demands()
    {
        return $this->hasMany(Demand::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
