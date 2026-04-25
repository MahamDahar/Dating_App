<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'color',
        'description',
    ];

    // ── Role ke permissions ──
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    // ── Is role mein yeh permission hai? ──
    public function hasPermission(string $permission): bool
    {
        return $this->permissions->contains('name', $permission);
    }

    // ── Is role ke users ──
    public function users()
    {
        return $this->hasMany(User::class);
    }
}