<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Absensi>
 */
class AbsensiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_karyawan' => 'U0001', // Adjust as needed to create realistic data
            'tanggal' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'), // Generate random dates from the last month to today
            'kehadiran' => $this->faker->boolean(80) ? 1 : 0, // 80% chance of being present (1), 20% chance of being absent (0)
        ];
    }
}
