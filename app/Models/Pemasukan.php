<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    use HasFactory;
    protected $table = 'pemasukan';

    protected $primaryKey = 'id';

    protected $fillable = [
        'tanggal',
        'jumlah',
        'sumber_pemasukan',
        'keterangan',
    ];
}
