<?php

namespace Database\Factories;

use App\Models\Buah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Buah>
 */
class BuahFactory extends Factory
{
    protected $model = Buah::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => 'BH-'.fake()->unique()->numerify('####'),
            'nama_buah' => fake()->randomElement([
                'Apel Fuji', 'Jeruk Mandarin', 'Pisang Cavendish', 'Mangga Harum Manis',
                'Anggur Merah', 'Semangka', 'Melon', 'Pepaya California', 'Nanas', 'Alpukat',
            ]),
            'kategori' => fake()->randomElement(['Buah Lokal', 'Buah Impor']),
            'harga' => fake()->numberBetween(8000, 60000),
            'stok' => fake()->numberBetween(0, 100),
            'satuan' => fake()->randomElement(['kg', 'pcs', 'ikat']),
            'gambar' => null,
            'keterangan' => null,
            'aktif' => true,
        ];
    }
}
