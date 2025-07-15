<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarGaji extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_karyawan', 'nama', 'jabatan',
        'gaji_pokok', 'jml_hr_kerja', 'jml_hadir',
        'jml_absen', 'jml_izin','jml_sakit', 'jml_terlambat',
        'jml_lembur', 'gaji_bersih', 'created_at', 'updated_at'
    ];

    protected $table = 'daftar_gaji';
    public function slipGaji()
    {
        return $this->hasMany(SlipGaji::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
