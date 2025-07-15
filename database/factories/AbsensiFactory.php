<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Absensi;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Absensi>
 */
class AbsensiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Absensi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Factory ini sekarang hanya fokus membuat data untuk status "Hadir".
        // Tanggal dan ID Karyawan akan diberikan oleh Seeder.
        $keteranganList = [];
        $date = Carbon::parse($this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'));

        // Tentukan kemungkinan terlambat (30% dari hari hadir)
        if (rand(1, 100) <= 30) {
            $keteranganList[] = 'Terlambat';
            // Jam masuk antara 09:16 - 10:00
            $jamMasuk = Carbon::parse($date->toDateString() . ' 09:16:00')->addMinutes(rand(0, 44));
        } else {
            // Jam masuk normal antara 08:00 - 09:15
            $jamMasuk = Carbon::parse($date->toDateString() . ' 08:00:00')->addMinutes(rand(0, 75));
        }

        // Tentukan kemungkinan lembur (40% dari hari hadir)
        if (rand(1, 100) <= 40) {
            $keteranganList[] = 'Lembur';
            // Jam keluar antara 17:31 - 19:00
            $jamKeluar = Carbon::parse($date->toDateString() . ' 17:31:00')->addMinutes(rand(0, 89));
        } else {
            // Jam pulang normal (8 jam setelah masuk)
            $jamKeluar = $jamMasuk->copy()->addHours(8);
        }

        return [
            // Nilai default ini akan ditimpa oleh data dari Seeder
            'id_karyawan' => User::inRandomOrder()->first()->id,
            'tanggal' => $date->toDateString(),
            'jam_masuk' => $jamMasuk->format('H:i:s'),
            'jam_keluar' => $jamKeluar->format('H:i:s'),
            'kehadiran' => 1, // Selalu dianggap hadir secara fisik
            'keterangan' => !empty($keteranganList) ? implode(', ', $keteranganList) : null,
        ];
    }
}
