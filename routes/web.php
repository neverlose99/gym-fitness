<?php

// ============================================
// routes/web.php
// ============================================

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Auth::routes();
// ============================================
// PUBLIC ROUTES
// ============================================

// Home & Static Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Classes Routes
Route::prefix('classes')->name('classes.')->group(function () {
    Route::get('/', [ClassController::class, 'index'])->name('index');
    Route::get('/schedule', [ClassController::class, 'schedule'])->name('schedule');
    Route::get('/{slug}', [ClassController::class, 'show'])->name('show');
});

// Trainers Routes
Route::prefix('trainers')->name('trainers.')->group(function () {
    Route::get('/', [TrainerController::class, 'index'])->name('index');
    Route::get('/{id}', [TrainerController::class, 'show'])->name('show');
});

// ============================================
// AUTHENTICATED ROUTES (Member/User)
// ============================================

Route::middleware(['auth'])->group(function () {
    
    // Member Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [MemberController::class, 'profile'])->name('index');
        Route::put('/update', [MemberController::class, 'updateProfile'])->name('update');
        Route::post('/renew-membership', [MemberController::class, 'renewMembership'])->name('renew');
    });

    // Booking Routes
    Route::prefix('bookings')->name('bookings.')->group(function () {
        // Tạo booking
        Route::get('/create/{classId}', [BookingController::class, 'create'])->name('create');
        Route::post('/', [BookingController::class, 'store'])->name('store');
        
        // Quản lý bookings
        Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('my');
        Route::get('/{id}', [BookingController::class, 'show'])->name('show');
        
        // Actions
        Route::post('/{id}/cancel', [BookingController::class, 'cancel'])->name('cancel');
        Route::post('/{id}/check-in', [BookingController::class, 'checkIn'])->name('checkin');
        Route::post('/{id}/review', [BookingController::class, 'review'])->name('review');
    });
});

// ============================================
// ADMIN ROUTES (Uncomment when you create Admin Controllers)
// ============================================

/*
// Import Admin Controllers first:
// use App\Http\Controllers\Admin\AdminController;
// use App\Http\Controllers\Admin\AdminMemberController;
// use App\Http\Controllers\Admin\AdminTrainerController;
// use App\Http\Controllers\Admin\AdminClassController;
// use App\Http\Controllers\Admin\AdminBookingController;
// use App\Http\Controllers\Admin\AdminReportController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Members Management
    Route::resource('members', AdminMemberController::class);
    
    // Trainers Management
    Route::resource('trainers', AdminTrainerController::class);
    
    // Classes Management
    Route::resource('classes', AdminClassController::class);
    
    // Bookings Management
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [AdminBookingController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminBookingController::class, 'show'])->name('show');
        Route::put('/{id}/confirm', [AdminBookingController::class, 'confirm'])->name('confirm');
        Route::put('/{id}/complete', [AdminBookingController::class, 'complete'])->name('complete');
        Route::delete('/{id}', [AdminBookingController::class, 'destroy'])->name('destroy');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/revenue', [AdminReportController::class, 'revenue'])->name('revenue');
        Route::get('/attendance', [AdminReportController::class, 'attendance'])->name('attendance');
        Route::get('/members', [AdminReportController::class, 'members'])->name('members');
    });
});
*/

// ============================================
// API ROUTES (for AJAX requests)
// ============================================

Route::prefix('api')->name('api.')->group(function () {
    
    // Check class availability
    Route::get('/classes/{id}/availability', function($id) {
        $class = \App\Models\GymClass::findOrFail($id);
        return response()->json([
            'available_slots' => $class->availableSlots(),
            'is_full' => $class->isFull(),
            'can_book' => $class->canBook(),
        ]);
    })->name('class.availability');
    
    // Get classes by filter (for AJAX)
    Route::get('/classes/filter', function(Request $request) {
        $query = \App\Models\GymClass::with('trainer')->active();
        
        if ($request->filled('level')) {
            $query->byLevel($request->level);
        }
        
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }
        
        return response()->json($query->get());
    })->name('classes.filter');
    
    // Get trainer's schedule
    Route::get('/trainers/{id}/schedule', function($id) {
        $trainer = \App\Models\Trainer::with('activeClasses')->findOrFail($id);
        return response()->json($trainer->activeClasses);
    })->name('trainer.schedule');
});

// ============================================
// FALLBACK ROUTE
// ============================================

Route::fallback(function () {
    return view('errors.404');
});