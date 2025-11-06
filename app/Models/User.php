<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'user';
    protected $primaryKey = 'iduser';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'email',
        'password',
        'idrole',
        'status'
    ];

    // Relasi ke Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'idrole', 'idrole');
    }

    // Relasi ke Pengadaan
    public function pengadaans()
    {
        return $this->hasMany(Pengadaan::class, 'user_iduser', 'iduser');
    }

    // Relasi ke Penjualan
    public function penjualans()
    {
        return $this->hasMany(Penjualan::class, 'iduser', 'iduser');
    }
}
