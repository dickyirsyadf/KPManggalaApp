<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontrakIklan extends Model
{
    use HasFactory;

    protected $table = 'kontrak_iklan';
    protected $primaryKey = 'id_kontrak';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_kontrak',
        'nama_client',
        'nama_media',
        'durasi',
        'biaya_iklan',
        'tanggal_mulai_kontrak',
        'tanggal_selesai_kontrak',
        'status',
        'diajukan_oleh',
        'dikonfirmasi_oleh',
    ];

    /**
     * Relasi ke tabel transaksi.
     */
    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'no_transaksi', 'id_kontrak');
    }
}
