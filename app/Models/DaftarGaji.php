<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarGaji extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_karyawan', 'nama', 'bagian', 'jumlah_hadir', 'gaji_perhari', 'absen', 'bonus', 'gaji_bersih'
    ];


    public function slipGaji()
    {
        return $this->hasMany(SlipGaji::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
