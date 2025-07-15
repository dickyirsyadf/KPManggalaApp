<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Absensi;
use Illuminate\Support\Carbon;

class AbsensiSeeder extends Seeder
{
    /**
     * Menjalankan seeder untuk mengisi data absensi yang realistis.
     */
    public function run(): void
    {
        Absensi::truncate();
        $users = User::all();
        $periode = now('Asia/Jakarta')->subMonth();
        $startOfMonth = $periode->copy()->startOfMonth();
        $endOfMonth = $periode->copy()->endOfMonth();

        $this->command->info('Mulai membuat data absensi untuk periode: ' . $periode->format('F Y'));

        foreach ($users as $user) {
            $this->command->line('Memproses karyawan: ' . $user->nama);

            // Ambil semua hari kerja (Senin-Sabtu) dalam sebulan
            $workingDays = [];
            for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
                if (!$date->isSunday()) {
                    $workingDays[] = $date->copy();
                }
            }
            shuffle($workingDays); // Acak urutan hari

            // 1. Tentukan hari IZIN (1 atau 2 hari, acak)
            $izinCount = rand(1, 2);
            $izinDays = array_splice($workingDays, 0, $izinCount);
            foreach ($izinDays as $date) {
                Absensi::create([
                    'id_karyawan' => $user->id,
                    'tanggal' => $date->toDateString(),
                    'kehadiran' => 0, // Kehadiran fisik 0, tapi tidak dihitung absen
                    'keterangan' => 'Izin',
                ]);
            }

            // 2. Tentukan hari SAKIT (0-2 hari, acak)
            $sakitCount = rand(0, 2);
            $sakitDays = array_splice($workingDays, 0, $sakitCount);
            foreach ($sakitDays as $date) {
                Absensi::create([
                    'id_karyawan' => $user->id,
                    'tanggal' => $date->toDateString(),
                    'kehadiran' => 0,
                    'keterangan' => 'Sakit',
                ]);
            }

            // 3. Tentukan hari ABSEN (1-3 hari, acak)
            // Hari-hari ini tidak akan dibuatkan record sama sekali
            $absenCount = rand(1, 3);
            array_splice($workingDays, 0, $absenCount);

            // 4. Sisa hari adalah HADIR
            foreach ($workingDays as $date) {
                // Panggil factory untuk membuat record Hadir (yang mungkin terlambat/lembur)
                Absensi::factory()->create([
                    'id_karyawan' => $user->id,
                    'tanggal' => $date->toDateString(),
                ]);
            }
        }
        $this->command->info('Data absensi realistis berhasil dibuat.');
    }
}
