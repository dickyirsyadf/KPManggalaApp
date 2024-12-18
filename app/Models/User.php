<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'no_hp_verified_at',
        'id_hakakses',
        'password',
        'created_at',
        'updated_at'
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function hakakses()
    {
        return $this->belongsTo(HakAkses::class, 'id_hakakses'); // Correct foreign key
    }
    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
    // Relasi dengan model DaftarGaji
    public function daftarGaji()
    {
        return $this->hasMany(DaftarGaji::class);
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
