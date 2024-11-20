<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customer';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'status'
    ];

    public function transaksi()
    {
        return $this->hasMany(Customer::class, 'id_customer');
    }
}
