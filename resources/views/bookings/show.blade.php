{{-- ============================================ --}}
{{-- 8. resources/views/bookings/show.blade.php --}}
{{-- ============================================ --}}

@extends('layouts.app')

@section('title', 'Chi tiết đặt lịch - ' . $booking->booking_code)

@push('styles')
<style>
    .booking-detail-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
    }

    .info-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    .info-card .card-header {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-bottom: none;
        border-radius: 15px 15px 0 0 !important;
        font-weight: bold;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #666;
    }

    .info-value {
        color: #333;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e0e0e0;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -25px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #4caf50;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #4caf50;
    }

    .timeline-item.pending::before {
        background: #ff9800;
        box-shadow: 0 0 0 2px #ff9800;
    }

    .timeline-item.inactive::before {
        background: #ddd;
        box-shadow: 0 0 0 2px #ddd;
    }

    .qr-code {
        text-align: center;
        padding: 2rem;
        background: white;
        border-radius: 10px;
    }

    .action-buttons .btn {
        margin-bottom: 0.5rem;
    }

    @media print {
        .no-print {
            display: none !important;
        }
        .booking-detail-header {
            background: #667eea !important;
            -webkit-print-color-adjust: exact;
        }
    }
</style>
@endpush

@section('content')
<div class="container my-5">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="no-print">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bookings.my') }}">Lịch của tôi</a></li>
            <li class="breadcrumb-item active">{{ $booking->booking_code }}</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="booking-detail-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold mb-3">
                    <i class="fas fa-ticket-alt"></i> Chi tiết đặt lịch
                </h2>
                <h4>{{ $booking->gymClass->name }}</h4>
                <p class="mb-0">
                    <i class="fas fa-barcode"></i> Mã đặt lịch: <strong>{{ $booking->booking_code }}</strong>
                </p>
            </div>
            <div class="col-md-4 text-end">
                <span class="badge status-{{ $booking->status }} fs-5 px-4 py-2">
                    {{ ucfirst($booking->status) }}
                </span>
                @if($booking->is_checked_in)
                    <div class="mt-2">
                        <span class="badge bg-success fs-6">
                            <i class="fas fa-check-circle"></i> Đã check-in
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">
            {{-- Class Information --}}
            <div class="card info-card">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i> Thông tin lớp học
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ $booking->gymClass->image_url }}" alt="{{ $booking->gymClass->name }}" 
                                 class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <h5 class="fw-bold mb-3">{{ $booking->gymClass->name }}</h5>
                            
                            <div class="info-row">
                                <span class="info-label"><i class="fas fa-user-tie"></i> Huấn luyện viên:</span>
                                <span class="info-value">{{ $booking->gymClass->trainer->name }}</span>
                            </div>
                            
                            <div class="info-row">
                                <span class="info-label"><i class="fas fa-chart-line"></i> Cấp độ:</span>
                                <span class="info-value">
                                    <span class="badge badge-{{ $booking->gymClass->level }}">
                                        {{ ucfirst($booking->gymClass->level) }}
                                    </span>
                                </span>
                            </div>
                            
                            <div class="info-row">
                                <span class="info-label"><i class="fas fa-layer-group"></i> Danh mục:</span>
                                <span class="info-value">{{ ucfirst($booking->gymClass->category) }}</span>
                            </div>
                            
                            <div class="info-row">
                                <span class="info-label"><i class="fas fa-hourglass-half"></i> Thời lượng:</span>
                                <span class="info-value">{{ $booking->gymClass->duration }} phút</span>
                            </div>

                            @if($booking->gymClass->room)
                            <div class="info-row">
                                <span class="info-label"><i class="fas fa-door-open"></i> Phòng:</span>
                                <span class="info-value">{{ $booking->gymClass->room }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Booking Information --}}
            <div class="card info-card">
                <div class="card-header">
                    <i class="fas fa-calendar-check"></i> Thông tin đặt lịch
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-calendar"></i> Ngày học:</span>
                        <span class="info-value fw-bold">{{ $booking->booking_date->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-clock"></i> Giờ học:</span>
                        <span class="info-value fw-bold">
                            {{ $booking->booking_time ? $booking->booking_time->format('H:i') : $booking->gymClass->start_time->format('H:i') }}
                        </span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-calendar-plus"></i> Ngày đặt:</span>
                        <span class="info-value">{{ $booking->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    @if($booking->member_notes)
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-comment"></i> Ghi chú của bạn:</span>
                        <span class="info-value">{{ $booking->member_notes }}</span>
                    </div>
                    @endif

                    @if($booking->admin_notes)
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-comment-dots"></i> Ghi chú từ admin:</span>
                        <span class="info-value">{{ $booking->admin_notes }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Payment Information --}}
            <div class="card info-card">
                <div class="card-header">
                    <i class="fas fa-credit-card"></i> Thông tin thanh toán
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-money-bill-wave"></i> Giá:</span>
                        <span class="info-value">
                            <h4 class="text-primary mb-0">{{ number_format($booking->price) }} ₫</h4>
                        </span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-receipt"></i> Trạng thái thanh toán:</span>
                        <span class="info-value">
                            @if($booking->payment_status === 'paid')
                                <span class="badge bg-success">Đã thanh toán</span>
                            @elseif($booking->payment_status === 'pending')
                                <span class="badge bg-warning">Chờ thanh toán</span>
                            @else
                                <span class="badge bg-danger">Đã hoàn tiền</span>
                            @endif
                        </span>
                    </div>

                    @if($booking->payment_method)
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-wallet"></i> Phương thức:</span>
                        <span class="info-value">{{ ucfirst($booking->payment_method) }}</span>
                    </div>
                    @endif

                    @if($booking->payment_date)
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-calendar-check"></i> Ngày thanh toán:</span>
                        <span class="info-value">{{ $booking->payment_date->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Status Timeline --}}
            <div class="card info-card">
                <div class="card-header">
                    <i class="fas fa-history"></i> Lịch sử trạng thái
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <strong>Đã tạo booking</strong>
                            <p class="text-muted mb-0">{{ $booking->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        @if($booking->status === 'confirmed' || $booking->status === 'completed')
                        <div class="timeline-item">
                            <strong>Đã xác nhận</strong>
                            <p class="text-muted mb-0">{{ $booking->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif

                        @if($booking->is_checked_in)
                        <div class="timeline-item">
                            <strong>Đã check-in</strong>
                            <p class="text-muted mb-0">{{ $booking->checked_in_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif

                        @if($booking->status === 'completed')
                        <div class="timeline-item">
                            <strong>Đã hoàn thành</strong>
                            <p class="text-muted mb-0">{{ $booking->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif

                        @if($booking->status === 'cancelled')
                        <div class="timeline-item pending">
                            <strong>Đã hủy</strong>
                            <p class="text-muted mb-0">{{ $booking->cancelled_at->format('d/m/Y H:i') }}</p>
                            @if($booking->cancellation_reason)
                                <p class="mb-0"><small>Lý do: {{ $booking->cancellation_reason }}</small></p>
                            @endif
                        </div>
                        @endif

                        @if($booking->rating)
                        <div class="timeline-item">
                            <strong>Đã đánh giá</strong>
                            <p class="text-muted mb-0">{{ $booking->reviewed_at->format('d/m/Y H:i') }}</p>
                            <p class="mb-0">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $booking->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Review Section --}}
            @if($booking->review)
            <div class="card info-card">
                <div class="card-header">
                    <i class="fas fa-star"></i> Đánh giá của bạn
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $booking->rating ? 'text-warning' : 'text-muted' }} fa-lg"></i>
                        @endfor
                    </div>
                    <p class="mb-0">{{ $booking->review }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- QR Code / Booking Code --}}
            <div class="card info-card sticky-top" style="top: 100px;">
                <div class="card-body qr-code">
                    <h5 class="fw-bold mb-3">Mã đặt lịch</h5>
                    <div class="mb-3">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $booking->booking_code }}" 
                             alt="QR Code" class="img-fluid">
                    </div>
                    <h4 class="fw-bold text-primary">{{ $booking->booking_code }}</h4>
                    <p class="text-muted small mb-0">Xuất trình mã này khi check-in</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="card info-card no-print">
                <div class="card-header">
                    <i class="fas fa-tasks"></i> Hành động
                </div>
                <div class="card-body action-buttons">
                    <div class="d-grid gap-2">
                        @if($booking->canCheckIn())
                            <form action="{{ route('bookings.checkin', $booking->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check-circle"></i> Check-in
                                </button>
                            </form>
                        @endif

                        @if($booking->canCancel())
                            <button type="button" class="btn btn-danger" 
                                    data-bs-toggle="modal" data-bs-target="#cancelModal">
                                <i class="fas fa-times-circle"></i> Hủy lịch
                            </button>
                        @endif

                        @if($booking->canReview())
                            <button type="button" class="btn btn-warning" 
                                    data-bs-toggle="modal" data-bs-target="#reviewModal">
                                <i class="fas fa-star"></i> Đánh giá
                            </button>
                        @endif

                        <button onclick="window.print()" class="btn btn-outline-primary">
                            <i class="fas fa-print"></i> In phiếu
                        </button>

                        <a href="{{ route('bookings.my') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>

                        <a href="{{ route('classes.show', $booking->gymClass->slug) }}" class="btn btn-outline-info">
                            <i class="fas fa-info-circle"></i> Xem lớp học
                        </a>
                    </div>
                </div>
            </div>

            {{-- Important Notes --}}
            <div class="alert alert-info no-print">
                <h6 class="fw-bold"><i class="fas fa-info-circle"></i> Lưu ý quan trọng:</h6>
                <ul class="mb-0 small">
                    <li>Vui lòng đến trước 10 phút</li>
                    <li>Mang theo đồ tập và nước</li>
                    <li>Có thể hủy trước 24 giờ</li>
                    <li>Liên hệ: 0123 456 789</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Cancel Modal --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Hủy lịch học</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Bạn có chắc muốn hủy lịch học này?</p>
                    
                    <div class="mb-3">
                        <label class="form-label">Lý do hủy (tùy chọn)</label>
                        <textarea name="cancellation_reason" class="form-control" rows="3" 
                                  placeholder="Vui lòng cho chúng tôi biết lý do..."></textarea>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i>
                        <strong>Chính sách hoàn tiền:</strong>
                        <ul class="mb-0">
                            <li>Hủy trước 24 giờ: Hoàn 100%</li>
                            <li>Hủy trong 24 giờ: Không hoàn tiền</li>
                        </ul>
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
<div class="modal fade" id="reviewModal" tabindex="-1">
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
                        <label class="form-label fw-bold">
                            Đánh giá của bạn <span class="text-danger">*</span>
                        </label>
                        <div class="rating-stars" id="ratingStars">
                            <i class="far fa-star fa-2x" data-rating="1"></i>
                            <i class="far fa-star fa-2x" data-rating="2"></i>
                            <i class="far fa-star fa-2x" data-rating="3"></i>
                            <i class="far fa-star fa-2x" data-rating="4"></i>
                            <i class="far fa-star fa-2x" data-rating="5"></i>
                        </div>
                        <input type="hidden" name="rating" id="ratingValue" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nhận xét (tùy chọn)</label>
                        <textarea name="review" class="form-control" rows="4" 
                                  placeholder="Chia sẻ trải nghiệm của bạn về lớp học..."></textarea>
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

@push('scripts')
<script>
// Star rating system
const stars = document.querySelectorAll('#ratingStars i');
const ratingInput = document.getElementById('ratingValue');

stars.forEach(star => {
    star.addEventListener('click', function() {
        const rating = this.dataset.rating;
        ratingInput.value = rating;

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
</script>
@endpush
@endsection