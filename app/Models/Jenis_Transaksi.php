<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis_Transaksi extends Model
{
    use HasFactory;

    protected $table = 'jenis_transaksi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'jenis_transaksi'
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_jenis_transaksi');
    }
}
