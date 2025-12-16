<?php

namespace App\Models;

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
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function fundFactSheets()
    {
        return $this->hasMany(FundFactSheet::class, 'uploaded_by');
    }

    public function laporanMingguan()
    {
        return $this->hasMany(LaporanMingguan::class, 'uploaded_by');
    }

    public function laporanBulanan()
    {
        return $this->hasMany(LaporanBulanan::class, 'uploaded_by');
    }

    public function laporanTahunan()
    {
        return $this->hasMany(LaporanTahunan::class, 'uploaded_by');
    }

    // Role Checkers
    public function isUser()
    {
        return $this->role === 'user';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    public function canManage()
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }
}