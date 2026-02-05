<?php

// ============================================
// 5. ADMIN MEMBER CONTROLLER
// ============================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by membership type
        if ($request->filled('membership_type')) {
            $query->where('membership_type', $request->membership_type);
        }

        $members = $query->latest()->paginate(15);

        return view('admin.members.index', compact('members'));
    }

    public function create()
    {
        return view('admin.members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'phone' => 'required|string|max:20',
            'membership_type' => 'required|in:basic,premium,vip',
            'membership_start' => 'required|date',
            'membership_end' => 'required|date|after:membership_start',
            'membership_price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        Member::create($validated);

        return redirect()->route('admin.members.index')
            ->with('success', 'Thêm thành viên thành công!');
    }

    public function show($id)
    {
        $member = Member::with(['bookings.gymClass'])->findOrFail($id);
        
        $stats = [
            'total_bookings' => $member->bookings()->count(),
            'completed_bookings' => $member->bookings()->where('status', 'completed')->count(),
            'total_spent' => $member->bookings()->where('payment_status', 'paid')->sum('price'),
        ];

        return view('admin.members.show', compact('member', 'stats'));
    }

    public function edit($id)
    {
        $member = Member::findOrFail($id);
        return view('admin.members.edit', compact('member'));
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $id,
            'phone' => 'required|string|max:20',
            'membership_type' => 'required|in:basic,premium,vip',
            'membership_start' => 'required|date',
            'membership_end' => 'required|date',
            'membership_price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $member->update($validated);

        return redirect()->route('admin.members.index')
            ->with('success', 'Cập nhật thành viên thành công!');
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Xóa thành viên thành công!');
    }

    public function toggleStatus($id)
    {
        $member = Member::findOrFail($id);
        $member->status = $member->status === 'active' ? 'inactive' : 'active';
        $member->save();

        return back()->with('success', 'Đã cập nhật trạng thái!');
    }
}

