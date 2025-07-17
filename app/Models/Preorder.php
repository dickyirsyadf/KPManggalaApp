<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preorder extends Model
{
    use HasFactory;
    protected $table = 'preorder';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tanggal',
        'nama_karyawan',
        'id_barang',
        'nama_barang',
        'jumlah',
        'harga_beli',
        'total_harga',
        'status',
        'status_dirubah_oleh',
        'created_at',
        'update_at'
    ];
}
