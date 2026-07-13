<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Toko',
            'email' => 'admin@tokobuah.test',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Siti Aminah',
            'email' => 'kasir@tokobuah.test',
            'role' => 'kasir',
        ]);
    }
}
