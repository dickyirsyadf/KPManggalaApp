<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity_Admin extends Model
{
    use HasFactory;

    protected $table = 'activity_admins';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'keterangan',
        'tanggal',
        'no_transaksi',
    ];
}
