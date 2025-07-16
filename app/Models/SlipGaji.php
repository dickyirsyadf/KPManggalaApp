<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlipGaji extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_karyawan',
        'nama',
        'jabatan',
        'periode',
        'gaji_pokok',
        'tunjangan_jabatan',
        'pendapatan_lembur',
        'total_pendapatan',
        'potongan_absen',
        'potongan_telat',
        'total_potongan',
        'gaji_bersih',
        'jumlah_hadir',
        'jumlah_sakit',
        'jumlah_izin',
        'jumlah_absen',
        'jumlah_lembur',
        'jumlah_terlambat',
    ];

    protected $table = 'slip_gaji';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
    ];

    // Nonaktifkan timestamps default jika Anda tidak memiliki kolom 'created_at' dan 'updated_at'
    // public $timestamps = false;
}
