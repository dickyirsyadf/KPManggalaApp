<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muzakki extends Model
{
    use HasFactory;
    protected $table = 'data_muzakkis';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
    ];

    public function transaksi()
    {
        return $this->hasMany(Zakat::class, 'id_muzakki');
    }
}
