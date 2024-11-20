<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;
    
    protected $casts = [
        'masuk' => 'hh:mm:ss',
        'pulang' => 'hh:mm:ss'
    ];
}
