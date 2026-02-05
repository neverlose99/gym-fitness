<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class GymSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    // 1. Tạo Trainer mẫu
    $trainerId = DB::table('trainers')->insertGetId([
        'name' => 'Nguyễn Văn A',
        'email' => 'trainer1@gmail.com',
        'phone' => '0123456789',
        'specialization' => 'Yoga',
        'status' => 'active',
        'created_at' => now(),
    ]);

    // 2. Tạo Lớp học mẫu
    DB::table('classes')->insert([
        'name' => 'Yoga Cơ Bản',
        'slug' => 'yoga-co-ban',
        'description' => 'Lớp học dành cho người mới bắt đầu',
        'trainer_id' => $trainerId,
        'level' => 'beginner',
        'category' => 'yoga',
        'duration' => 60,
        'start_time' => '08:00:00',
        'days_of_week' => json_encode(['monday', 'wednesday']),
        'start_date' => now(),
        'price' => 100000,
        'status' => 'active',
        'is_featured' => true,
        'image' => 'https://picsum.photos/seed/gym1/800/450',
        'created_at' => now(),
    ]);
}
}
