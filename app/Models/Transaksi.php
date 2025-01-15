<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;


class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_karyawan', 'no_transaksi', 'id_jenis_transaksi', 'nominal_transaksi', 'tanggal_transaksi',
    ];

    public static function getHariIni()
    {
        return self::where('id_jenis_transaksi', 1)
            ->whereDate('created_at', now()->toDateString())
            ->count();
    }

    public static function getMingguIni()
    {
        return self::where('id_jenis_transaksi', 1)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
    }

    public static function getBulanIni()
    {
        return self::where('id_jenis_transaksi', 1)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    function jenis_transaksi()
    {
        return $this->belongsTo(Jenis_Transaksi::class, 'id_jenis_transaksi');
    }

    function user()
    {
        return $this->belongsTo(User::class, 'id_karyawan');
    }
    public function detail_penjualans()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan', 'no_transaksi');
    }

}
