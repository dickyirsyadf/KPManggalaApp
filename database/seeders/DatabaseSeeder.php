<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

use App\Models\HakAkses;
use App\Models\User;
use App\Models\Jenis_Transaksi;
use App\Models\Transaksi;
use App\Models\Muzakki;
use App\Models\Barang;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder HAK AKSES
        HakAkses::factory()
            ->state(new Sequence(
                ['hakakses' => 'admin']
            ))
            ->create();
        HakAkses::factory()
            ->state(new Sequence(
                ['hakakses' => 'user']
            ))
            ->create();
        // HakAkses::factory()
        //     ->state(new Sequence(
        //         ['hakakses' => 'super admin']
        //     ))
        //     ->create();

        // Seeder USER
        User::factory()
            ->state(new Sequence(
                [
                    'email' => 'admin@gmail.com',
                    'id_hakakses' => 1,
                    'nama' => 'iniNamaAdmin',
                    'no_hp' => '080000000000',
                    'password' => bcrypt('12341234')
                ],
            ))
            ->create();
        User::factory()
            ->state(new Sequence(
                [
                    'email' => 'superadmin@gmail.com',
                    'id_hakakses' => 2,
                    'nama' => 'iniNamaSuperAdmin',
                    'no_hp' => '088888888888',
                    'password' => bcrypt('12341234'),
                ],
            ))
            ->create();

        // Seeder Jenis Transaksi
        Jenis_Transaksi::factory()
            ->state(new Sequence(
                ['jenis_transaksi' => 'Zakat Maal (Harta)']
            ))
            ->create();
        Jenis_Transaksi::factory()
            ->state(new Sequence(
                ['jenis_transaksi' => 'Zakat Fitrah']
            ))
            ->create();
        Jenis_Transaksi::factory()
            ->state(new Sequence(
                ['jenis_transaksi' => 'Zakat Penghasilan']
            ))
            ->create();
        Jenis_Transaksi::factory()
            ->state(new Sequence(
                ['jenis_transaksi' => 'Infaq']
            ))
            ->create();
        Jenis_Transaksi::factory()
            ->state(new Sequence(
                ['jenis_transaksi' => 'Sedekah']
            ))
            ->create();
        Jenis_Transaksi::factory()
            ->state(new Sequence(
                ['jenis_transaksi' => 'Fidyah']
            ))
            ->create();

        // Seeder Muzzaki
        Muzakki::factory(10)->create();

        // Seeder Barang
        Barang::factory(50)->create();
    }
}
