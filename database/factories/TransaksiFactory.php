<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Muzakki;
use App\Models\Jenis_Transaksi;

use Carbon\Carbon;
use Carbon\CarbonTimeZone;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaksi>
 */
class TransaksiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = Muzakki::inRandomOrder()->first();
        $jenis_transaksi = Jenis_Transaksi::inRandomOrder()->first();

        $timeZone = new CarbonTimeZone('Asia/Jakarta');
        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', '2022-01-01 10:01:00');
        $endDate = Carbon::now($timeZone)->format('Y-m-d H:i:s');
        $randDate = $this->faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d H:i:s');

        $randDates = str_replace(['-', ':', ' '], '', $randDate);
        $randNumbers = rand(11111, 99999);
        $idTransaksi = $randNumbers . $randDates;

        $minRange = 10000;
        $maxRange = 1000000;
        $steps = [5000, 10000, 15000, 20000, 25000];
        // Pilih langkah acak dari array $steps
        $randomStep = $steps[array_rand($steps)];
        // Hasil keluaran acak dengan langkah yang dipilih
        $randNominal = rand($minRange, $maxRange);
        $randNominal = floor($randNominal / $randomStep) * $randomStep;
        if ($randNominal < 10000) {
            $nominal = $minRange;
        } else {
            $nominal = $randNominal;
        }

        $status = ['Bayar', 'Proses'];

        return [
            'id_muzakki' => $user->id,
            'no_transaksi' => $idTransaksi,
            'id_jenis_transaksi' => $jenis_transaksi->id,
            'nominal_transaksi' => $nominal,
            'tanggal_transaksi' => $randDate,
            'status' => $this->faker->randomElement($status),
        ];
    }
}
