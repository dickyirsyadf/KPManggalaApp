<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlipGaji extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nama',
        'bagian',
        'jumlah_hadir',
        'tanggal',
        'penerimaan',
        'potongan',
        'total',
        'created_at',
        'update_at'
    ];

    public function daftarGaji()
{
    return $this->belongsTo(DaftarGaji::class);
}

}
