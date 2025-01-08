<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlipGaji extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_karyawan',
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

    protected $table = 'slip_gaji';


    public function daftarGaji()
{
    return $this->belongsTo(DaftarGaji::class);
}

}
