{{-- ============================================ --}}
{{-- 7. resources/views/bookings/my-bookings.blade.php --}}
{{-- ============================================ --}}

@extends('layouts.app')

@section('title', 'Lịch của tôi - Gym Fitness')

@push('styles')
<style>
    .booking-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: all 0.3s;
        margin-bottom: 1.5rem;
    }

    .booking-card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        transform: translateY(-3px);
    }

    .booking-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
    }

    .status-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .status-confirmed { background: #4caf50; color: white; }
    .status-pending { background: #ff9800; color: white; }
    .status-cancelled { background: #f44336; color: white; }
    .status-completed { background: #2196f3; color: white; }

    .filter-tabs {
        border-bottom: 2px solid #e0e0e0;
        margin-bottom: 2rem;
    }

    .filter-tab {
        padding: 1rem 1.5rem;
        border: none;
        background: none;
        color: #666;
        font-weight: 600;
        position: relative;
        cursor: pointer;
    }

    .filter-tab.active {
        color: var(--primary-color);
    }

    .filter-tab.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--primary-color);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 5rem;
        color: #ddd;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container my-5">
    <div class="booking-header rounded mb-4">
        <h1 class="display-5 fw-bold mb-2">
            <i class="fas fa-calendar-check"></i> Lịch của tôi
        </h1>
        <p class="mb-0">Quản lý tất cả lịch tập của bạn</p>
    </div>

    {{-- Filter Tabs --}}
    <div class="filter-tabs d-flex">
        <a href="{{ route('bookings.my') }}?time=upcoming" 
           class="filter-tab {{ request('time', 'upcoming') == 'upcoming' ? 'active' : '' }}">
            <i class="fas fa-clock"></i> Sắp tới
        </a>
        <a href="{{ route('bookings.my') }}?time=today" 
           class="filter-tab {{ request('time') == 'today' ? 'active' : '' }}">
            <i class="fas fa-calendar-day"></i> Hôm nay
        </a>
        <a href="{{ route('bookings.my') }}?time=past" 
           class="filter-tab {{ request('time') == 'past' ? 'active' : '' }}">
            <i class="fas fa-history"></i> Đã qua
        </a>
        <a href="{{ route('bookings.my') }}?status=all" 
           class="filter-tab {{ request('status') == 'all' ? 'active' : '' }}">
            <i class="fas fa-list"></i> Tất cả
        </a>
    </div>

    {{-- Status Filter --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('bookings.my') }}?status=all" 
                           class="btn btn-sm {{ request('status', 'all') == 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Tất cả
                        </a>
                        <a href="{{ route('bookings.my') }}?status=confirmed" 
                           class="btn btn-sm {{ request('status') == 'confirmed' ? 'btn-success' : 'btn-outline-success' }}">
                            Đã xác nhận
                        </a>
                        <a href="{{ route('bookings.my') }}?status=pending" 
                           class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                            Chờ xử lý
                        </a>
                        <a href="{{ route('bookings.my') }}?status=completed" 
                           class="btn btn-sm {{ request('status') == 'completed' ? 'btn-info' : 'btn-outline-info' }}">
                            Hoàn thành
                        </a>
                        <a href="{{ route('bookings.my') }}?status=cancelled" 
                           class="btn btn-sm {{ request('status') == 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }}">
                            Đã hủy
                        </a>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <span class="text-muted">Tổng: <strong>{{ $bookings->total() }}</strong> lịch</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Bookings List --}}
    @forelse($bookings as $booking)
        <div class="booking-card">
            <div class="card-body">
                <div class="row align-items-center">
                    {{-- Class Image --}}
                    <div class="col-md-2">
                        <img src="{{ $booking->gymClass->image_url }}" alt="{{ $booking->gymClass->name }}" 
                             class="img-fluid rounded">
                    </div>

                    {{-- Booking Info --}}
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-2">
                            <h5 class="fw-bold mb-0 me-3">{{ $booking->gymClass->name }}</h5>
                            <span class="status-badge status-{{ $booking->status }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>

                        <p class="text-muted mb-2">
                            <i class="fas fa-user-tie"></i> HLV: {{ $booking->gymClass->trainer->name }}
                        </p>

                        <div class="row g-3">
                            <div class="col-auto">
                                <i class="fas fa-calendar text-primary"></i>
                                <strong>{{ $booking->booking_date->format('d/m/Y') }}</strong>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock text-primary"></i>
                                {{ $booking->booking_time ? $booking->booking_time->format('H:i') : $booking->gymClass->start_time->format('H:i') }}
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-hourglass-half text-primary"></i>
                                {{ $booking->gymClass->duration }} phút
                            </div>
                        </div>

                        @if($booking->booking_code)
                            <p class="mt-2 mb-0">
                                <small class="text-muted">Mã đặt lịch: <strong>{{ $booking->booking_code }}</strong></small>
                            </p>
                        @endif
                    </div>

                    {{-- Price & Actions --}}
                    <div class="col-md-4 text-end">
                        <h4 class="text-primary mb-3">{{ number_format($booking->price) }} ₫</h4>

                        <div class="d-grid gap-2">
                            <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> Chi tiết
                            </a>

                            @if($booking->canCancel())
                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                        data-bs-toggle="modal" data-bs-target="#cancelModal{{ $booking->id }}">
                                    <i class="fas fa-times"></i> Hủy lịch
                                </button>
                            @endif

                            @if($booking->canCheckIn())
                                <form action="{{ route('bookings.checkin', $booking->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                        <i class="fas fa-check"></i> Check-in
                                    </button>
                                </form>
                            @endif

                            @if($booking->canReview())
                                <button type="button" class="btn btn-warning btn-sm" 
                                        data-bs-toggle="modal" data-bs-target="#reviewModal{{ $booking->id }}">
                                    <i class="fas fa-star"></i> Đánh giá
                                </button>
                            @endif
                        </div>

                        @if($booking->is_checked_in)
                            <div class="mt-2">
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle"></i> Đã check-in
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Cancel Modal --}}
        <div class="modal fade" id="cancelModal{{ $booking->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Hủy lịch học</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p>Bạn có chắc muốn hủy lịch học <strong>{{ $booking->gymClass->name }}</strong> vào ngày <strong>{{ $booking->booking_date->format('d/m/Y') }}</strong>?</p>
                            
                            <div class="mb-3">
                                <label class="form-label">Lý do hủy (tùy chọn)</label>
                                <textarea name="cancellation_reason" class="form-control" rows="3" 
                                          placeholder="Vui lòng cho chúng tôi biết lý do..."></textarea>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Lịch hủy trước 24 giờ sẽ được hoàn tiền 100%
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Review Modal --}}
        <div class="modal fade" id="reviewModal{{ $booking->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Đánh giá lớp học</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('bookings.review', $booking->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Đánh giá của bạn <span class="text-danger">*</span></label>
                                <div class="rating-stars" id="rating{{ $booking->id }}">
                                    <i class="far fa-star" data-rating="1"></i>
                                    <i class="far fa-star" data-rating="2"></i>
                                    <i class="far fa-star" data-rating="3"></i>
                                    <i class="far fa-star" data-rating="4"></i>
                                    <i class="far fa-star" data-rating="5"></i>
                                </div>
                                <input type="hidden" name="rating" id="ratingValue{{ $booking->id }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nhận xét (tùy chọn)</label>
                                <textarea name="review" class="form-control" rows="4" 
                                          placeholder="Chia sẻ trải nghiệm của bạn..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-calendar-times"></i>
            <h4 class="fw-bold mb-3">Chưa có lịch học nào</h4>
            <p class="text-muted mb-4">Hãy bắt đầu đặt lịch và trải nghiệm các lớp học tuyệt vời của chúng tôi!</p>
            <a href="{{ route('classes.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-calendar-plus"></i> Đặt lịch ngay
            </a>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($bookings->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $bookings->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
// Star rating system
document.querySelectorAll('.rating-stars').forEach(container => {
    const stars = container.querySelectorAll('i');
    const bookingId = container.id.replace('rating', '');
    const input = document.getElementById('ratingValue' + bookingId);

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            input.value = rating;

            stars.forEach(s => {
                if (s.dataset.rating <= rating) {
                    s.classList.remove('far');
                    s.classList.add('fas', 'text-warning');
                } else {
                    s.classList.remove('fas', 'text-warning');
                    s.classList.add('far');
                }
            });
        });

        star.addEventListener('mouseenter', function() {
            const rating = this.dataset.rating;
            stars.forEach(s => {
                if (s.dataset.rating <= rating) {
                    s.classList.add('text-warning');
                }
            });
        });

        star.addEventListener('mouseleave', function() {
            stars.forEach(s => {
                if (!s.classList.contains('fas')) {
                    s.classList.remove('text-warning');
                }
            });
        });
    });
});
</script>
@endpush
@endsection
