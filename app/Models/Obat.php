<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Obat extends Model
{
    use HasFactory;
    protected $table = 'obat';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'deskripsi',
        'stock',
        'harga_jual',
        'harga_modal',
        'created_at',
        'update_at'
    ];
    public function detailPenjualans(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'id_obat', 'id');
    }
}
