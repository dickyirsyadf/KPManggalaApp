<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

use App\Models\HakAkses;
use App\Models\User;
use App\Models\Jenis_Transaksi;
use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\Obat;

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
        HakAkses::factory()
            ->state(new Sequence(
                ['hakakses' => 'Super Admin']
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
                    'id'=>'U0001',
                    'email' => 'admin@gmail.com',
                    'id_hakakses' => 1,
                    'nama' => 'Admin',
                    'no_hp' => '080000000000',
                    'password' => bcrypt('12341234')
                ],
            ))
            ->create();
        User::factory()
            ->state(new Sequence(
                [
                    'id'=>'U0002',
                    'email' => 'user@gmail.com',
                    'id_hakakses' => 2,
                    'nama' => 'User',
                    'no_hp' => '088888888888',
                    'password' => bcrypt('12341234'),
                ],
            ))
            ->create();
        User::factory()
            ->state(new Sequence(
                [
                    'id'=>'U0003',
                    'email' => 'super@gmail.com',
                    'id_hakakses' => 3,
                    'nama' => 'User',
                    'no_hp' => '088888888888',
                    'password' => bcrypt('12341234'),
                ],
            ))
            ->create();

        // Seeder Jenis Transaksi
        Jenis_Transaksi::factory()
            ->state(new Sequence(
                ['jenis_transaksi' => 'Penjualan']
            ))
            ->create();
        Jenis_Transaksi::factory()
            ->state(new Sequence(
                ['jenis_transaksi' => 'Penggajian']
            ))
            ->create();

        // Seeder Obat
        Obat::factory(50)->create();
    }
}
