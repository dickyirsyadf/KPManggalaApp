<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tunjangan extends Model
{
    use HasFactory;
    protected $table = 'tunjangan';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_jabatan',
        'gaji_jabatan',
        'rate_lembur',
    ];
    public function jabatan()
    {
        return $this->hasMany(Jabatan::class, 'id_jabatan', 'id');
    }
}
