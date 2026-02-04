{{-- ============================================ --}}
{{-- 11. resources/views/trainers/show.blade.php --}}
{{-- ============================================ --}}

@extends('layouts.app')

@section('title', $trainer->name . ' - Huấn luyện viên')

@push('styles')
<style>
    .trainer-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
    }

    .trainer-avatar {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        border: 5px solid white;
        object-fit: cover;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .info-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .achievement-item {
        padding: 1rem;
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        border-radius: 10px;
        margin-bottom: 1rem;
        color: #333;
    }

    .class-item {
        border-left: 4px solid #667eea;
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        transition: all 0.3s;
    }

    .class-item:hover {
        background: #e9ecef;
        border-left-width: 6px;
    }

    .schedule-table {
        background: white;
        border-radius: 10px;
        overflow: hidden;
    }

    .schedule-table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem;
    }

    .timeline-item {
        padding-left: 30px;
        position: relative;
        margin-bottom: 2rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 8px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #667eea;
        border: 3px solid white;
        box-shadow: 0 0 0 3px #667eea;
    }
</style>
@endpush

@section('content')
{{-- Breadcrumb --}}
<div class="container mt-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('trainers.index') }}">Huấn luyện viên</a></li>
            <li class="breadcrumb-item active">{{ $trainer->name }}</li>
        </ol>
    </nav>
</div>

{{-- Header --}}
<div class="trainer-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                <img src="{{ $trainer->avatar_url }}" alt="{{ $trainer->name }}" class="trainer-avatar">
            </div>
            <div class="col-md-6">
                <h1 class="display-4 fw-bold mb-3">{{ $trainer->name }}</h1>
                <h4 class="mb-3">
                    <i class="fas fa-dumbbell"></i> {{ $trainer->specialization }}
                </h4>
                <div class="mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $trainer->rating ? 'text-warning' : 'text-light' }} fa-lg"></i>
                    @endfor
                    <span class="ms-2 fs-5">{{ number_format($trainer->rating, 1) }}</span>
                    <span class="opacity-75">({{ $trainer->total_reviews }} đánh giá)</span>
                </div>
                <p class="lead mb-0">
                    <i class="fas fa-award"></i> {{ $trainer->experience_years }} năm kinh nghiệm
                </p>
            </div>
            <div class="col-md-3 text-end">
                @if($trainer->facebook || $trainer->instagram || $trainer->youtube)
                <div class="mb-3">
                    @if($trainer->facebook)
                        <a href="{{ $trainer->facebook }}" target="_blank" class="btn btn-light btn-lg me-2">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif
                    @if($trainer->instagram)
                        <a href="{{ $trainer->instagram }}" target="_blank" class="btn btn-light btn-lg me-2">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                    @if($trainer->youtube)
                        <a href="{{ $trainer->youtube }}" target="_blank" class="btn btn-light btn-lg">
                            <i class="fab fa-youtube"></i>
                        </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">
            {{-- About --}}
            <div class="info-card">
                <h4 class="fw-bold mb-3"><i class="fas fa-user"></i> Giới thiệu</h4>
                <p class="text-muted">{{ $trainer->bio ?: 'Chưa có thông tin giới thiệu.' }}</p>
            </div>

            {{-- Certifications --}}
            @if($trainer->certifications && count($trainer->certifications) > 0)
            <div class="info-card">
                <h4 class="fw-bold mb-3"><i class="fas fa-certificate"></i> Chứng chỉ</h4>
                <div class="row">
                    @foreach($trainer->certifications as $cert)
                        <div class="col-md-6 mb-2">
                            <div class="achievement-item">
                                <i class="fas fa-medal me-2"></i> {{ $cert }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Achievements --}}
            @if($trainer->achievements)
            <div class="info-card">
                <h4 class="fw-bold mb-3"><i class="fas fa-trophy"></i> Thành tích</h4>
                <p class="text-muted">{{ $trainer->achievements }}</p>
            </div>
            @endif

            {{-- Classes Teaching --}}
            <div class="info-card">
                <h4 class="fw-bold mb-4">
                    <i class="fas fa-chalkboard-teacher"></i> Lớp học đang dạy 
                    <span class="badge bg-primary">{{ $stats['active_classes'] }}</span>
                </h4>

                @forelse($trainer->activeClasses as $class)
                    <div class="class-item">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="fw-bold mb-2">{{ $class->name }}</h5>
                                <p class="text-muted mb-2">{{ Str::limit($class->description, 150) }}</p>
                                <div class="mb-2">
                                    <span class="badge badge-{{ $class->level }} me-2">{{ ucfirst($class->level) }}</span>
                                    <span class="badge bg-info text-dark me-2">{{ ucfirst($class->category) }}</span>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-clock"></i> {{ $class->duration }} phút
                                    </span>
                                </div>
                                <div class="small text-muted">
                                    <i class="fas fa-calendar"></i>
                                    @foreach($class->days_of_week as $day)
                                        <span class="badge bg-light text-dark">{{ ucfirst($day) }}</span>
                                    @endforeach
                                    <span class="ms-2">
                                        <i class="fas fa-clock"></i> {{ $class->start_time->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <h4 class="text-primary mb-2">{{ number_format($class->price) }} ₫</h4>
                                <p class="text-muted mb-2 small">
                                    <i class="fas fa-users"></i> {{ $class->availableSlots() }}/{{ $class->max_participants }}
                                </p>
                                <a href="{{ route('classes.show', $class->slug) }}" class="btn btn-primary btn-sm">
                                    Xem lớp <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted py-4">Hiện chưa có lớp học nào</p>
                @endforelse
            </div>

            {{-- Reviews (if any) --}}
            <div class="info-card">
                <h4 class="fw-bold mb-4"><i class="fas fa-comments"></i> Đánh giá từ học viên</h4>
                
                @php
                    $reviews = \App\Models\Booking::whereHas('gymClass', function($q) use ($trainer) {
                        $q->where('trainer_id', $trainer->id);
                    })->whereNotNull('rating')->with('member')->latest('reviewed_at')->take(5)->get();
                @endphp

                @forelse($reviews as $review)
                    <div class="mb-4 pb-4 border-bottom">
                        <div class="d-flex align-items-start">
                            <img src="{{ $review->member->avatar_url }}" alt="{{ $review->member->name }}" 
                                 class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-bold mb-1">{{ $review->member->name }}</h6>
                                    <small class="text-muted">{{ $review->reviewed_at->diffForHumans() }}</small>
                                </div>
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }} small"></i>
                                    @endfor
                                </div>
                                @if($review->review)
                                    <p class="text-muted mb-0">{{ $review->review }}</p>
                                @endif
                                <small class="text-muted">Lớp: {{ $review->gymClass->name }}</small>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted py-4">Chưa có đánh giá nào</p>
                @endforelse
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Stats Card --}}
            <div class="info-card">
                <h5 class="fw-bold mb-4"><i class="fas fa-chart-bar"></i> Thống kê</h5>
                
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fas fa-graduation-cap fa-3x text-primary"></i>
                    </div>
                    <h2 class="fw-bold mb-1">{{ $stats['total_students'] }}+</h2>
                    <p class="text-muted">Học viên đã đào tạo</p>
                </div>

                <hr>

                <div class="d-flex justify-content-between mb-3">
                    <span><i class="fas fa-book text-primary"></i> Tổng lớp:</span>
                    <strong>{{ $stats['total_classes'] }}</strong>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span><i class="fas fa-play-circle text-success"></i> Đang dạy:</span>
                    <strong>{{ $stats['active_classes'] }}</strong>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span><i class="fas fa-star text-warning"></i> Đánh giá TB:</span>
                    <strong>{{ number_format($trainer->rating, 1) }}/5.0</strong>
                </div>

                <div class="d-flex justify-content-between">
                    <span><i class="fas fa-clock text-info"></i> Kinh nghiệm:</span>
                    <strong>{{ $trainer->experience_years }} năm</strong>
                </div>
            </div>

            {{-- Working Schedule --}}
            @if($trainer->working_days && count($trainer->working_days) > 0)
            <div class="info-card">
                <h5 class="fw-bold mb-3"><i class="fas fa-calendar-week"></i> Lịch làm việc</h5>
                
                <div class="mb-3">
                    <strong>Ngày làm việc:</strong>
                    <div class="mt-2">
                        @foreach($trainer->working_days as $day)
                            <span class="badge bg-primary me-1 mb-1">{{ ucfirst($day) }}</span>
                        @endforeach
                    </div>
                </div>

                @if($trainer->shift_start && $trainer->shift_end)
                <div>
                    <strong>Giờ làm việc:</strong>
                    <p class="mb-0 mt-2">
                        <i class="fas fa-clock"></i> 
                        {{ $trainer->shift_start->format('H:i') }} - {{ $trainer->shift_end->format('H:i') }}
                    </p>
                </div>
                @endif
            </div>
            @endif

            {{-- Contact --}}
            <div class="info-card">
                <h5 class="fw-bold mb-3"><i class="fas fa-envelope"></i> Liên hệ</h5>
                
                <p class="mb-2">
                    <i class="fas fa-envelope text-primary"></i>
                    <a href="mailto:{{ $trainer->email }}">{{ $trainer->email }}</a>
                </p>

                @if($trainer->phone)
                <p class="mb-0">
                    <i class="fas fa-phone text-success"></i>
                    <a href="tel:{{ $trainer->phone }}">{{ $trainer->phone }}</a>
                </p>
                @endif
            </div>

            {{-- CTA --}}
            <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="card-body text-center p-4">
                    <h5 class="fw-bold mb-3">Bạn quan tâm?</h5>
                    <p class="mb-4">Đăng ký lớp học với {{ $trainer->name }} ngay hôm nay!</p>
                    <a href="{{ route('classes.index') }}?trainer_id={{ $trainer->id }}" class="btn btn-light btn-lg w-100">
                        <i class="fas fa-calendar-plus"></i> Xem lớp học
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection