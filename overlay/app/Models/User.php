<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // role & is_active SENGAJA tidak masuk $fillable
    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function anggota(): HasOne { return $this->hasOne(Anggota::class); }
    public function simpananDibuat(): HasMany { return $this->hasMany(Simpanan::class, 'dibuat_oleh'); }
    public function simpananDiverifikasi(): HasMany { return $this->hasMany(Simpanan::class, 'diverifikasi_oleh'); }
    public function isAdmin(): bool { return $this->role === 'admin'; }
}
