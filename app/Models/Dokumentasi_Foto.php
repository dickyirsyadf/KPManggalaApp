<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumentasi_Foto extends Model
{
    use HasFactory;
    protected $table = 'dokumentasi_fotos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'folder',
        'foto'
    ];
}
