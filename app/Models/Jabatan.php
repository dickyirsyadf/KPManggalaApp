<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;
    protected $table = 'jabatan';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_jabatan',
        'gaji_pokok',
    ];
    public function users()
    {
        return $this->hasMany(User::class, 'id_jabatan', 'id');
    }
}
