<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    use HasFactory;

    protected $table = 'penerimaan';
    protected $primaryKey = 'idpenerimaan';
    public $timestamps = false;

    protected $fillable = ['created_at', 'status', 'idpengadaan', 'iduser'];

    public function details()
    {
        return $this->hasMany(DetailPenerimaan::class, 'idpenerimaan', 'idpenerimaan');
    }
}
