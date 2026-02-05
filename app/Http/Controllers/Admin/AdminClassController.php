<?php

// ============================================
// 7. ADMIN CLASS CONTROLLER
// ============================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GymClass;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminClassController extends Controller
{
    public function index(Request $request)
    {
        $query = GymClass::with('trainer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('trainer_id')) {
            $query->where('trainer_id', $request->trainer_id);
        }

        $classes = $query->latest()->paginate(15);
        $trainers = Trainer::active()->get();

        return view('admin.classes.index', compact('classes', 'trainers'));
    }

    public function create()
    {
        $trainers = Trainer::active()->get();
        return view('admin.classes.create', compact('trainers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'trainer_id' => 'required|exists:trainers,id',
            'level' => 'required|in:beginner,intermediate,advanced',
            'category' => 'required|in:yoga,cardio,strength,boxing,dance,crossfit,pilates,other',
            'duration' => 'required|integer|min:15',
            'start_time' => 'required|date_format:H:i',
            'days_of_week' => 'required|array',
            'max_participants' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'room' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['days_of_week'] = json_encode($validated['days_of_week']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('classes', 'public');
        }

        GymClass::create($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Thêm lớp học thành công!');
    }

    public function show($id)
    {
        $class = GymClass::with(['trainer', 'bookings'])->findOrFail($id);
        return view('admin.classes.show', compact('class'));
    }

    public function edit($id)
    {
        $class = GymClass::findOrFail($id);
        $trainers = Trainer::active()->get();
        return view('admin.classes.edit', compact('class', 'trainers'));
    }

    public function update(Request $request, $id)
    {
        $class = GymClass::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'trainer_id' => 'required|exists:trainers,id',
            'level' => 'required|in:beginner,intermediate,advanced',
            'category' => 'required|in:yoga,cardio,strength,boxing,dance,crossfit,pilates,other',
            'duration' => 'required|integer|min:15',
            'start_time' => 'required|date_format:H:i',
            'days_of_week' => 'required|array',
            'max_participants' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'room' => 'nullable|string',
            'status' => 'required|in:active,inactive,full,cancelled',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['days_of_week'] = json_encode($validated['days_of_week']);

        if ($request->hasFile('image')) {
            if ($class->image) {
                Storage::delete('public/' . $class->image);
            }
            $validated['image'] = $request->file('image')->store('classes', 'public');
        }

        $class->update($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Cập nhật lớp học thành công!');
    }

    public function destroy($id)
    {
        $class = GymClass::findOrFail($id);
        
        if ($class->image) {
            Storage::delete('public/' . $class->image);
        }
        
        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Xóa lớp học thành công!');
    }

    public function toggleStatus($id)
    {
        $class = GymClass::findOrFail($id);
        $class->status = $class->status === 'active' ? 'inactive' : 'active';
        $class->save();

        return back()->with('success', 'Đã cập nhật trạng thái!');
    }
}
