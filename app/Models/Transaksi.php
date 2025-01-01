<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_karyawan', 'no_transaksi', 'id_jenis_transaksi', 'nominal_transaksi', 'tanggal_transaksi',
    ];

    function jenis_transaksi()
    {
        return $this->belongsTo(Jenis_Transaksi::class, 'id_jenis_transaksi');
    }

    function user()
    {
        return $this->belongsTo(User::class, 'id_karyawan');
    }
}
