{{-- ============================================ --}}
{{-- 2. resources/views/home.blade.php --}}
{{-- ============================================ --}}

@extends('layouts.app')

@section('title', 'Trang chủ - Gym Fitness')

@section('content')
{{-- Hero Section --}}
<div class="hero-section">
    <h1>Chào mừng đến với Gym Fitness</h1>
    <p>Bắt đầu hành trình thay đổi cơ thể của bạn ngay hôm nay</p>
    <a href="{{ route('classes.index') }}" class="btn btn-primary btn-lg">
        <i class="fas fa-fire"></i> Xem lớp học ngay
    </a>
</div>

{{-- Stats Section --}}
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <h3>{{ $stats['total_members'] }}+</h3>
                    <p>Thành viên</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="fas fa-dumbbell"></i>
                    <h3>{{ $stats['total_classes'] }}+</h3>
                    <p>Lớp học</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="fas fa-user-tie"></i>
                    <h3>{{ $stats['total_trainers'] }}+</h3>
                    <p>HLV chuyên nghiệp</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="fas fa-calendar-check"></i>
                    <h3>{{ $stats['total_bookings_today'] }}</h3>
                    <p>Lượt đặt hôm nay</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Featured Classes --}}
<section class="container my-5">
    <div class="text-center mb-4">
        <h2 class="display-4 fw-bold">Lớp học nổi bật</h2>
        <p class="lead">Khám phá các lớp học phổ biến nhất</p>
    </div>
    
    <div class="row">
        @foreach($featuredClasses as $class)
        <div class="col-md-4">
            <div class="card class-card">
                <img src="{{ $class->image_url }}" class="card-img-top" alt="{{ $class->name }}">
                <div class="card-body class-card-body">
                    <span class="badge badge-{{ $class->level }}">{{ ucfirst($class->level) }}</span>
                    <h5 class="card-title mt-2">{{ $class->name }}</h5>
                    <p class="text-muted">
                        <i class="fas fa-user-tie"></i> {{ $class->trainer->name }}
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-clock"></i> {{ $class->duration }} phút</span>
                        <span><i class="fas fa-users"></i> {{ $class->availableSlots() }} chỗ trống</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h4 class="text-primary mb-0">{{ number_format($class->price) }} ₫</h4>
                        <a href="{{ route('classes.show', $class->slug) }}" class="btn btn-primary">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="text-center mt-4">
        <a href="{{ route('classes.index') }}" class="btn btn-outline-primary btn-lg">
            Xem tất cả lớp học <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</section>

{{-- Trainers Section --}}
<section class="container my-5 py-5" style="background: #f8f9fa; border-radius: 20px;">
    <div class="text-center mb-4">
        <h2 class="display-4 fw-bold">Huấn luyện viên của chúng tôi</h2>
        <p class="lead">Đội ngũ HLV chuyên nghiệp, tận tâm</p>
    </div>
    
    <div class="row px-4">
        @foreach($trainers as $trainer)
        <div class="col-md-3">
            <div class="card trainer-card">
                <img src="{{ $trainer->avatar_url }}" class="card-img-top" alt="{{ $trainer->name }}">
                <div class="card-body trainer-card-body text-center">
                    <h5 class="card-title">{{ $trainer->name }}</h5>
                    <p class="text-primary fw-bold">{{ $trainer->specialization }}</p>
                    <div class="mb-2">
                        <i class="fas fa-star text-warning"></i>
                        <span>{{ number_format($trainer->rating, 1) }}</span>
                        <span class="text-muted">({{ $trainer->total_reviews }})</span>
                    </div>
                    <p class="text-muted small">{{ $trainer->experience_years }} năm kinh nghiệm</p>
                    <a href="{{ route('trainers.show', $trainer->id) }}" class="btn btn-outline-primary btn-sm">
                        Xem hồ sơ
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="text-center mt-4">
        <a href="{{ route('trainers.index') }}" class="btn btn-outline-primary btn-lg">
            Xem tất cả HLV <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container text-center text-white">
        <h2 class="display-5 fw-bold mb-3">Sẵn sàng bắt đầu chưa?</h2>
        <p class="lead mb-4">Đăng ký ngay hôm nay và nhận ưu đãi đặc biệt!</p>
        @guest
            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">
                <i class="fas fa-user-plus"></i> Đăng ký ngay
            </a>
        @else
            <a href="{{ route('classes.index') }}" class="btn btn-light btn-lg px-5">
                <i class="fas fa-calendar-plus"></i> Đặt lịch ngay
            </a>
        @endguest
    </div>
</section>
@endsection
