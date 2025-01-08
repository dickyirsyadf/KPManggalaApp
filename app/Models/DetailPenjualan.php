<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPenjualan extends Model
{
    protected $fillable = ['id_penjualan', 'id_obat', 'qty', 'harga', 'subtotal','margin'];
    protected $table = 'detail_penjualan';

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan', 'id');
    }

    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class, 'id_obat', 'id');
    }
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_penjualan', 'id');
    }

}
