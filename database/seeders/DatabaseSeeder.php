<?php

// File: database/seeders/DatabaseSeeder.php
// GANTI ISI FILE INI (file sudah ada, tinggal edit)

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil semua seeder
        $this->call([
            UserSeeder::class,
            NasabahSeeder::class,
        ]);
    }
}