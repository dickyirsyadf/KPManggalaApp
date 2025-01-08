<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    use HasFactory;
    protected $fillable = ['id_karyawan', 'nama', 'bagian', 'tgl_terima_gaji'];
    protected $table = 'penggajian';
}
