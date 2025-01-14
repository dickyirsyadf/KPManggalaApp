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
        return self::whereDate('created_at', Carbon::today())->count();
    }
    public static function getMingguIni()
    {
        return self::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
    }
    public static function getBulanIni()
    {
        return self::whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
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
