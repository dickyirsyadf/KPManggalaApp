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
        'id_muzakki',
        'no_transaksi',
        'id_jenis_transaksi',
        'nominal_transaksi',
        'tanggal_transaksi',
        'status',
        'keterangan',
    ];

    function jenis_transaksi()
    {
        return $this->belongsTo(Jenis_Transaksi::class, 'id_jenis_transaksi');
    }

    function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    function muzakki()
    {
        return $this->belongsTo(Muzakki::class, 'id_muzakki');
    }
}
