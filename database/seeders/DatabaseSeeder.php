<?php

// ============================================
// 1. database/seeders/DatabaseSeeder.php
// ============================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TrainerSeeder::class,
            ClassSeeder::class,
            BookingSeeder::class,
        ]);
    }
}