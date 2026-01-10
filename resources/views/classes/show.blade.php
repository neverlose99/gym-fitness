{{-- ============================================ --}}
{{-- 4. resources/views/classes/show.blade.php --}}
{{-- ============================================ --}}

@extends('layouts.app')

@section('title', $class->name . ' - Gym Fitness')

@section('content')
<div class="container my-5">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">Lớp học</a></li>
            <li class="breadcrumb-item active">{{ $class->name }}</li>
        </ol>
    </nav>

    <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">
            {{-- Class Image --}}
            <div class="card mb-4">
                <img src="{{ $class->image_url }}" class="card-img-top" alt="{{ $class->name }}" 
                     style="height: 400px; object-fit: cover; border-radius: 15px;">
            </div>

            {{-- Class Info --}}
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="display-5 fw-bold mb-2">{{ $class->name }}</h1>
                            <div class="mb-2">
                                <span class="badge badge-{{ $class->level }} me-2">{{ ucfirst($class->level) }}</span>
                                <span class="badge bg-info text-dark">{{ ucfirst($class->category) }}</span>
                                @if($class->is_featured)
                                    <span class="badge bg-warning text-dark">Nổi bật</span>
                                @endif
                                @if($class->is_online)
                                    <span class="badge bg-success">Online</span>
                                @endif
                            </div>
                            <div class="mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $class->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span class="ms-2">{{ number_format($class->rating, 1) }}</span>
                                <span class="text-muted">({{ $class->total_reviews }} đánh giá)</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <h2 class="text-primary mb-0">{{ number_format($class->price) }} ₫</h2>
                            <small class="text-muted">/ buổi</small>
                            @if($class->package_price)
                                <div class="mt-2">
                                    <span class="badge bg-success">Gói tháng: {{ number_format($class->package_price) }} ₫</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="row text-center mb-4 p-3 bg-light rounded">
                        <div class="col-3">
                            <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                            <p class="mb-0 fw-bold">{{ $class->duration }}</p>
                            <small class="text-muted">Phút</small>
                        </div>
                        <div class="col-3">
                            <i class="fas fa-users fa-2x text-primary mb-2"></i>
                            <p class="mb-0 fw-bold">{{ $class->availableSlots() }}/{{ $class->max_participants }}</p>
                            <small class="text-muted">Chỗ trống</small>
                        </div>
                        <div class="col-3">
                            <i class="fas fa-fire fa-2x text-primary mb-2"></i>
                            <p class="mb-0 fw-bold">{{ $class->calories_burn ?? 'N/A' }}</p>
                            <small class="text-muted">Calo</small>
                        </div>
                        <div class="col-3">
                            <i class="fas fa-chart-line fa-2x text-primary mb-2"></i>
                            <p class="mb-0 fw-bold">{{ ucfirst($class->level) }}</p>
                            <small class="text-muted">Cấp độ</small>
                        </div>
                    </div>

                    {{-- Description --}}
                    <h4 class="fw-bold mb-3"><i class="fas fa-info-circle"></i> Mô tả</h4>
                    <p class="text-muted">{{ $class->description }}</p>

                    @if($class->benefits)
                        <h4 class="fw-bold mb-3 mt-4"><i class="fas fa-check-circle text-success"></i> Lợi ích</h4>
                        <p class="text-muted">{{ $class->benefits }}</p>
                    @endif

                    @if($class->requirements)
                        <h4 class="fw-bold mb-3 mt-4"><i class="fas fa-clipboard-list"></i> Yêu cầu</h4>
                        <p class="text-muted">{{ $class->requirements }}</p>
                    @endif
                </div>
            </div>

            {{-- Trainer Info --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-3"><i class="fas fa-user-tie"></i> Huấn luyện viên</h4>
                    <div class="d-flex align-items-center">
                        <img src="{{ $class->trainer->avatar_url }}" alt="{{ $class->trainer->name }}" 
                             class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <h5 class="mb-1">{{ $class->trainer->name }}</h5>
                            <p class="text-primary mb-1">{{ $class->trainer->specialization }}</p>
                            <div class="mb-1">
                                <i class="fas fa-star text-warning"></i>
                                <span>{{ number_format($class->trainer->rating, 1) }}</span>
                                <span class="text-muted">({{ $class->trainer->total_reviews }} đánh giá)</span>
                            </div>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-award"></i> {{ $class->trainer->experience_years }} năm kinh nghiệm
                            </p>
                        </div>
                        <a href="{{ route('trainers.show', $class->trainer->id) }}" class="btn btn-outline-primary">
                            Xem hồ sơ
                        </a>
                    </div>
                </div>
            </div>

            {{-- Schedule --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-3"><i class="fas fa-calendar-alt"></i> Lịch học</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Ngày học:</strong></p>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($class->days_of_week as $day)
                                    <span class="badge bg-primary">{{ ucfirst($day) }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Giờ học:</strong> {{ $class->start_time->format('H:i') }}</p>
                            @if($class->room)
                                <p><strong>Phòng:</strong> {{ $class->room }}</p>
                            @endif
                            @if($class->location)
                                <p><strong>Vị trí:</strong> {{ $class->location }}</p>
                            @endif
                        </div>
                    </div>
                    @if($class->start_date || $class->end_date)
                        <hr>
                        <p class="mb-0 text-muted">
                            <i class="fas fa-info-circle"></i>
                            @if($class->start_date)
                                Bắt đầu: {{ $class->start_date->format('d/m/Y') }}
                            @endif
                            @if($class->end_date)
                                - Kết thúc: {{ $class->end_date->format('d/m/Y') }}
                            @endif
                        </p>
                    @endif
                </div>
            </div>

            {{-- Reviews Section --}}
            <div class="card">
                <div class="card-body">
                    <h4 class="fw-bold mb-3"><i class="fas fa-comments"></i> Đánh giá từ học viên</h4>
                    
                    @php
                        $reviews = \App\Models\Booking::where('class_id', $class->id)
                            ->whereNotNull('rating')
                            ->with('member')
                            ->latest('reviewed_at')
                            ->take(5)
                            ->get();
                    @endphp

                    @forelse($reviews as $review)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-start">
                                <img src="{{ $review->member->avatar_url }}" alt="{{ $review->member->name }}" 
                                     class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">{{ $review->member->name }}</h6>
                                        <small class="text-muted">{{ $review->reviewed_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }} small"></i>
                                        @endfor
                                    </div>
                                    @if($review->review)
                                        <p class="mb-0 text-muted">{{ $review->review }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted py-4">
                            <i class="fas fa-comment-slash fa-3x mb-3 d-block"></i>
                            Chưa có đánh giá nào
                        </p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Booking Card --}}
            <div class="card sticky-top" style="top: 100px;">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">Đặt lịch ngay</h4>
                    
                    @if($class->canBook())
                        @auth
                            <form action="{{ route('bookings.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="class_id" value="{{ $class->id }}">
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Chọn ngày <span class="text-danger">*</span></label>
                                    <input type="date" name="booking_date" class="form-control" 
                                           min="{{ date('Y-m-d') }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ghi chú (tùy chọn)</label>
                                    <textarea name="member_notes" class="form-control" rows="3" 
                                              placeholder="Có điều gì bạn muốn HLV biết?"></textarea>
                                </div>

                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-calendar-check"></i> Đặt lịch ngay
                                    </button>
                                </div>

                                <div class="text-center">
                                    <small class="text-muted">
                                        <i class="fas fa-shield-alt"></i> Đặt lịch an toàn & bảo mật
                                    </small>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để đặt lịch
                            </div>
                            <div class="d-grid">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg mb-2">
                                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                    Chưa có tài khoản? Đăng ký ngay
                                </a>
                            </div>
                        @endauth
                    @else
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle"></i>
                            @if($class->status !== 'active')
                                Lớp học hiện không hoạt động
                            @elseif($class->isFull())
                                Lớp học đã đầy
                            @else
                                Không thể đặt lịch lớp này
                            @endif
                        </div>
                    @endif

                    <hr>

                    {{-- Class Features --}}
                    <div class="mb-3">
                        <h6 class="fw-bold mb-3">Đặc điểm lớp học</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check text-success"></i> 
                                HLV chuyên nghiệp
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success"></i> 
                                Thiết bị hiện đại
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success"></i> 
                                Không gian thoáng mát
                            </li>
                            @if($class->is_free_trial)
                                <li class="mb-2">
                                    <i class="fas fa-check text-success"></i> 
                                    Học thử miễn phí
                                </li>
                            @endif
                            <li class="mb-2">
                                <i class="fas fa-check text-success"></i> 
                                Hủy lịch linh hoạt
                            </li>
                        </ul>
                    </div>

                    <div class="alert alert-info mb-0">
                        <small>
                            <i class="fas fa-info-circle"></i>
                            <strong>Lưu ý:</strong> Bạn có thể hủy lịch trước 24 giờ để được hoàn tiền
                        </small>
                    </div>
                </div>
            </div>

            {{-- Share Social --}}
            <div class="card mt-3">
                <div class="card-body text-center">
                    <h6 class="fw-bold mb-3">Chia sẻ lớp học</h6>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="#" class="btn btn-primary btn-sm">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="btn btn-info btn-sm text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-danger btn-sm">
                            <i class="fab fa-pinterest"></i>
                        </a>
                        <button class="btn btn-secondary btn-sm" onclick="copyLink()">
                            <i class="fas fa-link"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Related Classes --}}
    @if($relatedClasses->count() > 0)
        <div class="mt-5">
            <h3 class="fw-bold mb-4">Lớp học liên quan</h3>
            <div class="row">
                @foreach($relatedClasses as $relatedClass)
                    <div class="col-md-3">
                        <div class="card class-card">
                            <img src="{{ $relatedClass->image_url }}" class="card-img-top" alt="{{ $relatedClass->name }}">
                            <div class="card-body class-card-body">
                                <span class="badge badge-{{ $relatedClass->level }}">{{ ucfirst($relatedClass->level) }}</span>
                                <h6 class="card-title mt-2">{{ $relatedClass->name }}</h6>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-user-tie"></i> {{ $relatedClass->trainer->name }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-primary fw-bold">{{ number_format($relatedClass->price) }} ₫</span>
                                    <a href="{{ route('classes.show', $relatedClass->slug) }}" class="btn btn-sm btn-primary">
                                        Xem
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function copyLink() {
    navigator.clipboard.writeText(window.location.href);
    alert('Đã copy link lớp học!');
}
</script>
@endpush
@endsection