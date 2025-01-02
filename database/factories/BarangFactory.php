<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->word(),
            'deskripsi' => $this->faker->word(),
            'stock' => $this->faker->numberBetween(1, 100),
            'harga_jual' => $this->faker->numberBetween(100000, 300000),
            'harga_modal' => $this->faker->numberBetween(50000, 150000),
        ];
    }
}
