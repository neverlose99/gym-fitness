<?php

// ============================================
// 6. ADMIN TRAINER CONTROLLER
// ============================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminTrainerController extends Controller
{
    public function index(Request $request)
    {
        $query = Trainer::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $trainers = $query->latest()->paginate(15);

        return view('admin.trainers.index', compact('trainers'));
    }

    public function create()
    {
        return view('admin.trainers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email',
            'phone' => 'required|string|max:20',
            'specialization' => 'required|string',
            'bio' => 'nullable|string',
            'experience_years' => 'required|integer|min:0',
            'base_salary' => 'required|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('trainers', 'public');
        }

        Trainer::create($validated);

        return redirect()->route('admin.trainers.index')
            ->with('success', 'Thêm HLV thành công!');
    }

    public function show($id)
    {
        $trainer = Trainer::with(['classes'])->findOrFail($id);
        return view('admin.trainers.show', compact('trainer'));
    }

    public function edit($id)
    {
        $trainer = Trainer::findOrFail($id);
        return view('admin.trainers.edit', compact('trainer'));
    }

    public function update(Request $request, $id)
    {
        $trainer = Trainer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email,' . $id,
            'phone' => 'required|string|max:20',
            'specialization' => 'required|string',
            'bio' => 'nullable|string',
            'experience_years' => 'required|integer|min:0',
            'base_salary' => 'required|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'avatar' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive,on_leave',
        ]);

        if ($request->hasFile('avatar')) {
            if ($trainer->avatar) {
                Storage::delete('public/' . $trainer->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('trainers', 'public');
        }

        $trainer->update($validated);

        return redirect()->route('admin.trainers.index')
            ->with('success', 'Cập nhật HLV thành công!');
    }

    public function destroy($id)
    {
        $trainer = Trainer::findOrFail($id);
        
        if ($trainer->avatar) {
            Storage::delete('public/' . $trainer->avatar);
        }
        
        $trainer->delete();

        return redirect()->route('admin.trainers.index')
            ->with('success', 'Xóa HLV thành công!');
    }

    public function toggleStatus($id)
    {
        $trainer = Trainer::findOrFail($id);
        $trainer->status = $trainer->status === 'active' ? 'inactive' : 'active';
        $trainer->save();

        return back()->with('success', 'Đã cập nhật trạng thái!');
    }
}
