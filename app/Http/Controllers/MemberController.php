<?php

// ============================================
// 5. app/Http/Controllers/MemberController.php
// ============================================

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Trang profile
    public function profile()
    {
        $member = Member::with(['bookings.gymClass'])
            ->findOrFail(Auth::id());

        $stats = [
            'total_bookings' => $member->bookings()->count(),
            'completed_classes' => $member->bookings()->where('status', 'completed')->count(),
            'upcoming_bookings' => $member->bookings()->upcoming()->count(),
        ];

        return view('members.profile', compact('member', 'stats'));
    }

    // Cập nhật profile
    public function updateProfile(Request $request)
    {
        $member = Member::findOrFail(Auth::id());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'health_notes' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Xử lý upload avatar
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ
            if ($member->avatar) {
                Storage::delete('public/' . $member->avatar);
            }
            
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $member->update($validated);

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    // Gia hạn membership
    public function renewMembership(Request $request)
    {
        $member = Member::findOrFail(Auth::id());

        $validated = $request->validate([
            'membership_type' => 'required|in:basic,premium,vip',
            'duration_months' => 'required|integer|min:1|max:12',
        ]);

        // Tính giá (có thể custom theo từng loại)
        $prices = [
            'basic' => 500000,
            'premium' => 1000000,
            'vip' => 2000000,
        ];

        $totalPrice = $prices[$validated['membership_type']] * $validated['duration_months'];

        // Cập nhật membership
        $newEndDate = $member->membership_end > now() 
            ? $member->membership_end->addMonths($validated['duration_months'])
            : now()->addMonths($validated['duration_months']);

        $member->update([
            'membership_type' => $validated['membership_type'],
            'membership_end' => $newEndDate,
            'membership_price' => $totalPrice,
            'status' => 'active',
        ]);

        return back()->with('success', 'Gia hạn thành công! Thẻ hết hạn: ' . $newEndDate->format('d/m/Y'));
    }
}