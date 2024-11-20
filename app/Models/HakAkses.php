<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HakAkses extends Model
{
    use HasFactory;
    protected $table = 'hakakses';

    protected $primaryKey = 'id';

    protected $fillable = [
        'hakakses'
    ];
    public function users()
    {
        return $this->hasMany(User::class, 'id_hakakses', 'id');
    }

}
