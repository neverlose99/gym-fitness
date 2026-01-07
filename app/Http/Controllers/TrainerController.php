<?php

// ============================================
// 3. app/Http/Controllers/TrainerController.php
// ============================================

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    public function index(Request $request)
    {
        $query = Trainer::active();

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('specialization', 'like', '%' . $search . '%');
            });
        }

        // Lọc theo specialization
        if ($request->filled('specialization')) {
            $query->bySpecialization($request->specialization);
        }

        // Sắp xếp
        $sort = $request->get('sort', 'rating');
        switch ($sort) {
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'experience':
                $query->orderBy('experience_years', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
        }

        $trainers = $query->paginate(12);

        return view('trainers.index', compact('trainers'));
    }

    public function show($id)
    {
        $trainer = Trainer::with(['activeClasses', 'classes'])
            ->findOrFail($id);

        // Thống kê
        $stats = [
            'total_classes' => $trainer->classes()->count(),
            'total_students' => $trainer->totalStudents(),
            'active_classes' => $trainer->activeClasses()->count(),
        ];

        return view('trainers.show', compact('trainer', 'stats'));
    }
}
