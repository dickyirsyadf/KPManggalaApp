<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

use App\Models\HakAkses;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Jenis_Transaksi;
use App\Models\Tunjangan;
use App\Models\Potongan;
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
        // Seeder Potongan
        Potongan::factory()
            ->state(new Sequence(
                [
                    'potongan_absensi' => 50000,
                    'potongan_terlambat' => 15000,
                ]
            ))
            ->create();
        // Seeder HAK AKSES
        HakAkses::factory()
            ->state(new Sequence(
                ['hakakses' => 'Admin']
                ))
                ->create();
        HakAkses::factory()
            ->state(new Sequence(
                ['hakakses' => 'User']
                ))
                ->create();
        // Seeder JABATAN
        Jabatan::factory()
            ->state(new Sequence(
                [
                    'nama_jabatan' => 'Manager',
                    'gaji_pokok' => 1500000,
                ]
            ))
            ->create();
        Jabatan::factory()
        ->state(new Sequence(
                [
                    'nama_jabatan' => 'Staff',
                    'gaji_pokok' => 1000000,
                ],
            ))
            ->create();

        //Seeder Tunjangan
        Tunjangan::factory()
            ->state(new Sequence(
                [
                    'id_jabatan' => 1,
                    'tunjangan_jabatan' => 1250000,
                    'rate_lembur' => 50000,
                ]
            ))
            ->create();
        Tunjangan::factory()
            ->state(new Sequence(
                [
                    'id_jabatan' => 2,
                    'tunjangan_jabatan' => 750000,
                    'rate_lembur' => 30000,
                ]
            ))
            ->create();
        // Seeder USER
        User::factory()
            ->state(new Sequence(
                [
                    'id'=>'U0001',
                    'email' => 'admin@gmail.com',
                    'id_hakakses' => 1,
                    'id_jabatan' => 1,
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
                    'id_jabatan' => 2,
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
        Jenis_Transaksi::factory()
            ->state(new Sequence(
                ['jenis_transaksi' => 'Kontrak Iklan']
            ))
            ->create();
        Jenis_Transaksi::factory()
            ->state(new Sequence(
                ['jenis_transaksi' => 'PreOrder']
            ))
            ->create();


    }
}
