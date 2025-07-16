<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    use HasFactory;
    protected $fillable = [
    'id_penggajian',
    'id_karyawan',
    'nama',
    'jabatan',
    'periode_gaji',
    'tgl_terima_gaji'];
    protected $table = 'penggajian';

    public function users()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
