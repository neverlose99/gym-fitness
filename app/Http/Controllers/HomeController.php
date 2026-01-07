<?php

// ============================================
// 1. app/Http/Controllers/HomeController.php
// ============================================

namespace App\Http\Controllers;

use App\Models\GymClass;
use App\Models\Trainer;
use App\Models\Member;
use App\Models\Booking;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy các lớp học nổi bật
        $featuredClasses = GymClass::with('trainer')
            ->active()
            ->featured()
            ->take(6)
            ->get();
        
        // Lấy huấn luyện viên top rated
        $trainers = Trainer::active()
            ->topRated(4)
            ->get();

        // Thống kê tổng quan
        $stats = [
            'total_members' => Member::active()->count(),
            'total_classes' => GymClass::active()->count(),
            'total_trainers' => Trainer::active()->count(),
            'total_bookings_today' => Booking::today()->count(),
        ];

        return view('home', compact('featuredClasses', 'trainers', 'stats'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }
}
