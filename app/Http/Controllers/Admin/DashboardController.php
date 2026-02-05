<?php

// ============================================
// 4. DASHBOARD CONTROLLER
// ============================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Trainer;
use App\Models\GymClass;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Tổng quan
        $stats = [
            'total_members' => Member::count(),
            'active_members' => Member::active()->count(),
            'total_trainers' => Trainer::count(),
            'active_trainers' => Trainer::active()->count(),
            'total_classes' => GymClass::count(),
            'active_classes' => GymClass::active()->count(),
            'total_bookings' => Booking::count(),
            'today_bookings' => Booking::today()->count(),
        ];

        // Doanh thu
        $revenue = [
            'today' => Booking::whereDate('created_at', today())
                ->where('payment_status', 'paid')
                ->sum('price'),
            'this_week' => Booking::whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->where('payment_status', 'paid')->sum('price'),
            'this_month' => Booking::whereMonth('created_at', Carbon::now()->month)
                ->where('payment_status', 'paid')
                ->sum('price'),
            'this_year' => Booking::whereYear('created_at', Carbon::now()->year)
                ->where('payment_status', 'paid')
                ->sum('price'),
        ];

        // Bookings theo tháng (12 tháng gần nhất)
        $bookingsByMonth = Booking::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('count', 'month');

        // Doanh thu theo tháng
        $revenueByMonth = Booking::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(price) as total')
        )
        ->where('payment_status', 'paid')
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('total', 'month');

        // Top classes
        $topClasses = GymClass::withCount(['bookings' => function($q) {
            $q->whereIn('status', ['confirmed', 'completed']);
        }])
        ->orderBy('bookings_count', 'desc')
        ->take(5)
        ->get();

        // Top trainers
        $topTrainers = Trainer::withCount(['classes' => function($q) {
            $q->where('status', 'active');
        }])
        ->orderBy('rating', 'desc')
        ->take(5)
        ->get();

        // Recent bookings
        $recentBookings = Booking::with(['member', 'gymClass'])
            ->latest()
            ->take(10)
            ->get();

        // Members expiring soon (30 days)
        $expiringMembers = Member::where('membership_end', '<=', Carbon::now()->addDays(30))
            ->where('membership_end', '>=', Carbon::now())
            ->where('status', 'active')
            ->orderBy('membership_end')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'revenue',
            'bookingsByMonth',
            'revenueByMonth',
            'topClasses',
            'topTrainers',
            'recentBookings',
            'expiringMembers'
        ));
    }
}
