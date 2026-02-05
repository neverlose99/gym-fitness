<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Member;
use App\Models\GymClass;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $members = Member::where('id', '>', 1)->get(); // Skip admin
        $classes = GymClass::all();

        $bookings = [];
        $statuses = ['confirmed', 'completed', 'cancelled'];
        $paymentStatuses = ['paid', 'pending'];

        // Tạo bookings cho mỗi member
        foreach ($members as $member) {
            // 2-4 bookings cho mỗi member
            $bookingCount = rand(2, 4);

            for ($i = 0; $i < $bookingCount; $i++) {
                $class = $classes->random();
                $status = $statuses[array_rand($statuses)];
                
                // Booking date: past, today, or future
                $dateOffset = rand(-30, 30);
                $bookingDate = Carbon::now()->addDays($dateOffset);

                // Adjust status based on date
                if ($dateOffset < -7) {
                    $status = 'completed';
                } elseif ($dateOffset < 0) {
                    $status = ['completed', 'cancelled'][array_rand(['completed', 'cancelled'])];
                } else {
                    $status = 'confirmed';
                }

                $booking = [
                    // 🔑 THÊM booking_code UNIQUE
                    'booking_code' => $this->generateBookingCode(),

                    'member_id' => $member->id,
                    'class_id' => $class->id,
                    'booking_date' => $bookingDate,
                    'booking_time' => $class->start_time,
                    'price' => $class->price,
                    'payment_status' => $status === 'cancelled'
                        ? 'refunded'
                        : $paymentStatuses[array_rand($paymentStatuses)],
                    'status' => $status,
                    'created_at' => $bookingDate->copy()->subDays(rand(1, 7)),
                ];

                // Add check-in for completed
                if ($status === 'completed') {
                    $booking['is_checked_in'] = true;
                    $booking['checked_in_at'] = $bookingDate->copy()->subMinutes(10);
                    $booking['payment_status'] = 'paid';
                    $booking['payment_date'] = $bookingDate->copy()->subDays(1);

                    // Add rating (70% chance)
                    if (rand(1, 10) <= 7) {
                        $booking['rating'] = rand(4, 5);
                        $booking['review'] = $this->getRandomReview();
                        $booking['reviewed_at'] = $bookingDate->copy()->addDays(1);
                    }
                }

                // Add cancellation reason
                if ($status === 'cancelled') {
                    $booking['cancellation_reason'] = $this->getRandomCancelReason();
                    $booking['cancelled_at'] = $bookingDate->copy()->subDays(rand(1, 3));
                }

                $bookings[] = $booking;
            }
        }

        foreach ($bookings as $booking) {
            Booking::create($booking);
        }

        $this->command->info('Created ' . count($bookings) . ' bookings');
    }

    // Hàm sinh booking_code luôn UNIQUE
    private function generateBookingCode(): string
    {
        // Ví dụ: BK202602051640301234
        return 'BK' . now()->format('YmdHis') . rand(1000, 9999);
    }

    private function getRandomReview()
    {
        $reviews = [
            'Lớp học rất tuyệt vời! HLV nhiệt tình và chuyên nghiệp.',
            'Tôi rất hài lòng với lớp này. Sẽ tiếp tục tham gia.',
            'HLV giảng dạy rất dễ hiểu, phù hợp cho người mới bắt đầu.',
            'Môi trường tập luyện thoải mái, mọi người rất thân thiện.',
            'Hiệu quả tập luyện tốt, tôi đã thấy sự tiến bộ rõ rệt.',
            'Lớp học chất lượng cao, đáng đồng tiền.',
            'HLV quan tâm đến từng học viên, chỉnh sửa tư thế cẩn thận.',
            'Tập xong thấy rất sảng khoái và tràn đầy năng lượng!',
        ];

        return $reviews[array_rand($reviews)];
    }

    private function getRandomCancelReason()
    {
        $reasons = [
            'Có việc đột xuất phải xử lý',
            'Không sắp xếp được thời gian',
            'Bận công việc',
            'Sức khỏe không tốt',
            'Có kế hoạch khác',
        ];

        return $reasons[array_rand($reasons)];
    }
}
