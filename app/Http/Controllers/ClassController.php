<?php

// ============================================
// 2. app/Http/Controllers/ClassController.php
// ============================================

namespace App\Http\Controllers;

use App\Models\GymClass;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $query = GymClass::with('trainer')->active();

        // Lọc theo level
        if ($request->filled('level')) {
            $query->byLevel($request->level);
        }

        // Lọc theo category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Lọc theo trainer
        if ($request->filled('trainer_id')) {
            $query->byTrainer($request->trainer_id);
        }

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Sắp xếp
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('current_participants', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $classes = $query->paginate(12);

        // Lấy danh sách trainers và categories cho filter
        $trainers = \App\Models\Trainer::active()->get();
        $categories = GymClass::select('category')->distinct()->pluck('category');
        $levels = ['beginner', 'intermediate', 'advanced'];

        return view('classes.index', compact('classes', 'trainers', 'categories', 'levels'));
    }

    public function show($slug)
    {
        $class = GymClass::with(['trainer', 'activeBookings'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Lấy các lớp liên quan (cùng category hoặc cùng trainer)
        $relatedClasses = GymClass::with('trainer')
            ->active()
            ->where('id', '!=', $class->id)
            ->where(function($q) use ($class) {
                $q->where('category', $class->category)
                  ->orWhere('trainer_id', $class->trainer_id);
            })
            ->limit(4)
            ->get();

        return view('classes.show', compact('class', 'relatedClasses'));
    }

    public function schedule()
    {
        // Lấy lịch học theo ngày trong tuần
        $schedule = GymClass::with('trainer')
            ->active()
            ->get()
            ->groupBy(function($class) {
                return $class->days_of_week;
            });

        return view('classes.schedule', compact('schedule'));
    }
}
