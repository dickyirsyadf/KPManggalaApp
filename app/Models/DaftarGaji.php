<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarGaji extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nama', 'bagian', 'jumlah_hadir', 'gaji_perhari', 'absen', 'bonus', 'gaji_bersih'
    ];

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
