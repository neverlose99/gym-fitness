<?php

// ============================================
// 4. app/Http/Controllers/BookingController.php
// ============================================

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\GymClass;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Hiển thị form đặt lịch
    public function create($classId)
    {
        $class = GymClass::with('trainer')->findOrFail($classId);

        if (!$class->canBook()) {
            return redirect()->back()
                ->with('error', 'Lớp học này không thể đặt lịch!');
        }

        return view('bookings.create', compact('class'));
    }

    // Xử lý đặt lịch
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'nullable|date_format:H:i',
            'member_notes' => 'nullable|string|max:500',
        ]);

        $class = GymClass::findOrFail($validated['class_id']);

        // Kiểm tra lớp học có thể đặt không
        if (!$class->canBook()) {
            return back()->with('error', 'Lớp học này không thể đặt lịch!');
        }

        // Kiểm tra còn chỗ trống không
        if ($class->availableSlots() <= 0) {
            return back()->with('error', 'Lớp học đã đầy!');
        }

        // LẤY MEMBER TỪ USER HIỆN TẠI
        $member = Auth::user()->member;

        // Kiểm tra member có tồn tại không
        if (!$member) {
            // Tự động tạo member nếu chưa có
            $member = Member::create([
                'user_id' => Auth::id(),
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => '',
                'membership_type' => 'basic',
                'membership_start' => now(),
                'membership_end' => now()->addMonth(1),
                'membership_price' => 0,
                'status' => 'active',
            ]);
        }

        // Kiểm tra member đã đặt lớp này vào ngày này chưa
        $existingBooking = Booking::where('member_id', $member->id)
            ->where('class_id', $validated['class_id'])
            ->where('booking_date', $validated['booking_date'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existingBooking) {
            return back()->with('error', 'Bạn đã đặt lớp học này vào ngày đã chọn!');
        }

        try {
            DB::beginTransaction();

            // Tạo booking
            $booking = Booking::create([
                'member_id' => $member->id, // SỬ DỤNG MEMBER ID
                'class_id' => $validated['class_id'],
                'booking_date' => $validated['booking_date'],
                'booking_time' => $validated['booking_time'] ?? $class->start_time,
                'price' => $class->price,
                'member_notes' => $validated['member_notes'] ?? null,
                'status' => 'confirmed',
                'payment_status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('bookings.show', $booking->id)
                ->with('success', 'Đặt lịch thành công! Mã đặt lịch: ' . $booking->booking_code);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Hiển thị chi tiết booking
    public function show($id)
    {
        $member = Auth::user()->member;

        if (!$member) {
            return redirect()->route('home')
                ->with('error', 'Không tìm thấy thông tin thành viên!');
        }

        $booking = Booking::with(['gymClass.trainer', 'member'])
            ->where('member_id', $member->id)
            ->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }

    // Danh sách booking của user
    public function myBookings(Request $request)
    {
        // LẤY MEMBER TỪ USER
        $member = Auth::user()->member;

        if (!$member) {
            // Tự động tạo member nếu chưa có
            $member = Member::create([
                'user_id' => Auth::id(),
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => '',
                'membership_type' => 'basic',
                'membership_start' => now(),
                'membership_end' => now()->addMonth(1),
                'membership_price' => 0,
                'status' => 'active',
            ]);
        }

        $query = Booking::with(['gymClass', 'gymClass.trainer'])
            ->where('member_id', $member->id);

        // Lọc theo status
        $status = $request->get('status', 'all');
        if ($status !== 'all') {
            $query->byStatus($status);
        }

        // Lọc theo thời gian
        $time = $request->get('time', 'upcoming');
        switch ($time) {
            case 'upcoming':
                $query->upcoming();
                break;
            case 'past':
                $query->past();
                break;
            case 'today':
                $query->today();
                break;
        }

        $bookings = $query->orderBy('booking_date', 'desc')->paginate(10);

        return view('bookings.my-bookings', compact('bookings'));
    }

    // Hủy booking
    public function cancel(Request $request, $id)
    {
        $member = Auth::user()->member;

        if (!$member) {
            return back()->with('error', 'Không tìm thấy thông tin thành viên!');
        }

        $booking = Booking::where('member_id', $member->id)
            ->findOrFail($id);

        if (!$booking->canCancel()) {
            return back()->with('error', 'Không thể hủy lịch này! (Phải hủy trước 24 giờ)');
        }

        $validated = $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        $booking->cancel($validated['cancellation_reason'] ?? null);

        return back()->with('success', 'Hủy lịch thành công!');
    }

    // Check-in
    public function checkIn($id)
    {
        $member = Auth::user()->member;

        if (!$member) {
            return back()->with('error', 'Không tìm thấy thông tin thành viên!');
        }

        $booking = Booking::where('member_id', $member->id)
            ->findOrFail($id);

        if (!$booking->canCheckIn()) {
            return back()->with('error', 'Không thể check-in booking này!');
        }

        $booking->checkIn();

        return back()->with('success', 'Check-in thành công!');
    }

    // Đánh giá sau khi hoàn thành
    public function review(Request $request, $id)
    {
        $member = Auth::user()->member;

        if (!$member) {
            return back()->with('error', 'Không tìm thấy thông tin thành viên!');
        }

        $booking = Booking::where('member_id', $member->id)
            ->findOrFail($id);

        if (!$booking->canReview()) {
            return back()->with('error', 'Không thể đánh giá lớp học này!');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $booking->update([
            'rating' => $validated['rating'],
            'review' => $validated['review'] ?? null,
            'reviewed_at' => now(),
        ]);

        // Cập nhật rating cho class
        $this->updateClassRating($booking->class_id);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá!');
    }

    // Helper: Cập nhật rating cho class
    private function updateClassRating($classId)
    {
        $class = GymClass::findOrFail($classId);
        
        $reviews = Booking::where('class_id', $classId)
            ->whereNotNull('rating')
            ->get();

        if ($reviews->count() > 0) {
            $class->update([
                'rating' => $reviews->avg('rating'),
                'total_reviews' => $reviews->count(),
            ]);
        }
    }
}