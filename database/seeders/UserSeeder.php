<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ===== Admin user =====
        $admin = User::updateOrCreate(
            ['email' => 'admin@gym.com'],
            [
                'name' => 'Admin Gym',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        Member::updateOrCreate(
            ['email' => 'admin@gym.com'],
            [
                'user_id' => $admin->id,
                'name' => 'Admin Gym',
                'phone' => '0123456789',
                'membership_type' => 'vip',
                'membership_start' => now()->subMonths(3),
                'membership_end' => now()->addMonths(9),
                'membership_price' => 24000000,
                'status' => 'active',
            ]
        );

        // ===== Test users =====
        $users = [
            [
                'name' => 'Nguyễn Văn A',
                'email' => 'nguyenvana@gmail.com',
                'phone' => '0987654321',
                'membership_type' => 'premium',
                'gender' => 'male',
                'height' => 175,
                'weight' => 70,
            ],
            [
                'name' => 'Trần Thị B',
                'email' => 'tranthib@gmail.com',
                'phone' => '0976543210',
                'membership_type' => 'basic',
                'gender' => 'female',
                'height' => 165,
                'weight' => 55,
            ],
            [
                'name' => 'Lê Văn C',
                'email' => 'levanc@gmail.com',
                'phone' => '0965432109',
                'membership_type' => 'vip',
                'gender' => 'male',
                'height' => 180,
                'weight' => 75,
            ],
            [
                'name' => 'Phạm Thị D',
                'email' => 'phamthid@gmail.com',
                'phone' => '0954321098',
                'membership_type' => 'premium',
                'gender' => 'female',
                'height' => 160,
                'weight' => 50,
            ],
            [
                'name' => 'Hoàng Văn E',
                'email' => 'hoangvane@gmail.com',
                'phone' => '0943210987',
                'membership_type' => 'basic',
                'gender' => 'male',
                'height' => 170,
                'weight' => 65,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            Member::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'user_id' => $user->id,
                    'name' => $userData['name'],
                    'phone' => $userData['phone'],
                    'membership_type' => $userData['membership_type'],
                    'membership_start' => now()->subMonth(),
                    'membership_end' => now()->addMonths(11),
                    'membership_price' => $this->getMembershipPrice($userData['membership_type']),
                    'gender' => $userData['gender'],
                    'height' => $userData['height'],
                    'weight' => $userData['weight'],
                    'status' => 'active',
                ]
            );
        }

        $this->command->info('Seeded users & members successfully');
    }

    private function getMembershipPrice($type)
    {
        return match($type) {
            'basic' => 6000000,
            'premium' => 12000000,
            'vip' => 24000000,
        };
    }
}