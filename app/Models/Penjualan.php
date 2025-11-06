<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $primaryKey = 'idpenjualan';
    public $timestamps = false;

    protected $fillable = [
        'created_at', 'subtotal_nilai', 'ppn', 'total_nilai', 'iduser', 'idmargin_penjualan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    public function details()
    {
        return $this->hasMany(DetailPenjualan::class, 'idpenjualan', 'idpenjualan');
    }
}
