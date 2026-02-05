<?php

// ============================================
// 3. database/seeders/TrainerSeeder.php
// ============================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trainer;

class TrainerSeeder extends Seeder
{
    public function run(): void
    {
        $trainers = [
            [
                'name' => 'Nguyễn Minh Tuấn',
                'email' => 'tuanpt@gym.com',
                'phone' => '0912345678',
                'specialization' => 'Yoga & Pilates',
                'bio' => 'HLV Yoga với 8 năm kinh nghiệm, chuyên về Hatha Yoga và Vinyasa Flow. Đã đào tạo hơn 500 học viên và có chứng chỉ RYT 500 từ Yoga Alliance.',
                'experience_years' => 8,
                'certifications' => json_encode([
                    'RYT 500 - Yoga Alliance',
                    'Pilates Mat Instructor',
                    'Prenatal Yoga Certified',
                ]),
                'achievements' => 'Giải nhất Yoga Competition Việt Nam 2020. Founder của Mindful Yoga Studio.',
                'base_salary' => 15000000,
                'commission_rate' => 20,
                'hire_date' => now()->subYears(3),
                'working_days' => json_encode(['monday', 'wednesday', 'friday']),
                'shift_start' => '06:00',
                'shift_end' => '12:00',
                'rating' => 4.8,
                'total_reviews' => 45,
                'status' => 'active',
            ],
            [
                'name' => 'Trần Hùng Cường',
                'email' => 'cuongtran@gym.com',
                'phone' => '0923456789',
                'specialization' => 'Strength Training & Bodybuilding',
                'bio' => 'Cựu vận động viên thể hình với 10 năm kinh nghiệm. Chuyên về tăng cơ, giảm mỡ và tập cho người mới bắt đầu.',
                'experience_years' => 10,
                'certifications' => json_encode([
                    'NASM Certified Personal Trainer',
                    'Sports Nutrition Specialist',
                    'Bodybuilding Coach Level 3',
                ]),
                'achievements' => 'Top 3 Mr. Vietnam 2018. Huấn luyện nhiều VĐV đạt huy chương quốc gia.',
                'base_salary' => 18000000,
                'commission_rate' => 25,
                'hire_date' => now()->subYears(5),
                'working_days' => json_encode(['monday', 'tuesday', 'thursday', 'saturday']),
                'shift_start' => '14:00',
                'shift_end' => '21:00',
                'rating' => 4.9,
                'total_reviews' => 67,
                'status' => 'active',
            ],
            [
                'name' => 'Lê Thị Mai',
                'email' => 'maile@gym.com',
                'phone' => '0934567890',
                'specialization' => 'Cardio & Dance Fitness',
                'bio' => 'HLV Zumba và HIIT với năng lượng tràn đầy. Giúp bạn đốt cháy calo một cách vui vẻ và hiệu quả.',
                'experience_years' => 6,
                'certifications' => json_encode([
                    'Zumba Fitness Instructor',
                    'HIIT Specialist',
                    'Group Fitness Instructor',
                ]),
                'achievements' => 'Giải nhì Dance Fitness Competition 2021. Instructor của năm 2022.',
                'base_salary' => 12000000,
                'commission_rate' => 18,
                'hire_date' => now()->subYears(2),
                'working_days' => json_encode(['tuesday', 'thursday', 'saturday', 'sunday']),
                'shift_start' => '17:00',
                'shift_end' => '21:00',
                'rating' => 4.7,
                'total_reviews' => 38,
                'status' => 'active',
            ],
            [
                'name' => 'Phạm Đức Anh',
                'email' => 'anhpham@gym.com',
                'phone' => '0945678901',
                'specialization' => 'Boxing & Kickboxing',
                'bio' => 'Cựu võ sĩ Boxing chuyên nghiệp với 12 năm kinh nghiệm. Dạy từ cơ bản đến nâng cao, phù hợp cho cả nam và nữ.',
                'experience_years' => 12,
                'certifications' => json_encode([
                    'Professional Boxing License',
                    'Kickboxing Instructor Level 4',
                    'Self Defense Trainer',
                ]),
                'achievements' => 'Vô địch Boxing Đông Nam Á 2015. Huấn luyện 3 võ sĩ vô địch quốc gia.',
                'base_salary' => 20000000,
                'commission_rate' => 30,
                'hire_date' => now()->subYears(4),
                'working_days' => json_encode(['monday', 'wednesday', 'friday', 'saturday']),
                'shift_start' => '18:00',
                'shift_end' => '22:00',
                'rating' => 4.9,
                'total_reviews' => 52,
                'status' => 'active',
            ],
            [
                'name' => 'Vũ Thị Hằng',
                'email' => 'hangvu@gym.com',
                'phone' => '0956789012',
                'specialization' => 'CrossFit & Functional Training',
                'bio' => 'HLV CrossFit Level 2 với passion về functional fitness. Giúp bạn phát triển toàn diện sức mạnh, sức bền và tính linh hoạt.',
                'experience_years' => 5,
                'certifications' => json_encode([
                    'CrossFit Level 2 Trainer',
                    'Functional Movement Specialist',
                    'Olympic Weightlifting Coach',
                ]),
                'achievements' => 'Top 10 CrossFit Vietnam Open 2022. Coach of the Month nhiều lần.',
                'base_salary' => 14000000,
                'commission_rate' => 22,
                'hire_date' => now()->subYears(2),
                'working_days' => json_encode(['monday', 'wednesday', 'friday']),
                'shift_start' => '05:00',
                'shift_end' => '11:00',
                'rating' => 4.6,
                'total_reviews' => 29,
                'status' => 'active',
            ],
        ];

        foreach ($trainers as $trainer) {
            // add placeholder avatar URLs for better display in demo
            $trainer['avatar'] = 'https://i.pravatar.cc/300?u=' . urlencode($trainer['email']);
            Trainer::create($trainer);
        }

        $this->command->info('Created ' . count($trainers) . ' trainers');
    }
}
