<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barang';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'deskripsi',
        'stock',
        'harga',
        'created_at',
        'update_at'
    ];
}
