<?php

// ============================================
// 1. MIDDLEWARE - app/Http/Middleware/AdminMiddleware.php
// ============================================

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập!');
        }

        // Check if user is admin (email contains 'admin')
        if (!str_contains(Auth::user()->email, 'admin')) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập!');
        }

        return $next($request);
    }
}
