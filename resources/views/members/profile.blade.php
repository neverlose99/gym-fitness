{{-- ============================================ --}}
{{-- 9. resources/views/members/profile.blade.php --}}
{{-- ============================================ --}}

@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân - ' . $member->name)

@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
    }

    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 5px solid white;
        object-fit: cover;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }

    .stat-card {
        border: none;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s;
        margin-bottom: 1.5rem;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .membership-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .membership-card.basic {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    }

    .membership-card.premium {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    }

    .membership-card.vip {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
    }

    .info-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
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

    .booking-item {
        border-left: 4px solid #667eea;
        padding: 1rem;
        margin-bottom: 1rem;
        background: #f8f9fa;
        border-radius: 5px;
        transition: all 0.3s;
    }

    .booking-item:hover {
        background: #e9ecef;
        border-left-width: 6px;
    }

    .nav-tabs .nav-link {
        color: #666;
        border: none;
        border-bottom: 3px solid transparent;
    }

    .nav-tabs .nav-link.active {
        color: #667eea;
        border-bottom-color: #667eea;
        font-weight: bold;
    }

    .progress-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
        margin: 0 auto 1rem;
    }

    .health-metric {
        text-align: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
{{-- Profile Header --}}
<div class="profile-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="profile-avatar">
            </div>
            <div class="col-md-6">
                <h2 class="fw-bold mb-2">{{ $member->name }}</h2>
                <p class="mb-1"><i class="fas fa-envelope"></i> {{ $member->email }}</p>
                @if($member->phone)
                    <p class="mb-1"><i class="fas fa-phone"></i> {{ $member->phone }}</p>
                @endif
                <div class="mt-3">
                    <span class="badge bg-light text-dark me-2">
                        <i class="fas fa-user"></i> Member ID: #{{ $member->id }}
                    </span>
                    @if($member->isActive())
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle"></i> Hoạt động
                        </span>
                    @else
                        <span class="badge bg-danger">
                            <i class="fas fa-times-circle"></i> Hết hạn
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-3 text-end">
                <button type="button" class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="fas fa-edit"></i> Chỉnh sửa
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    {{-- Stats Overview --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <i class="fas fa-dumbbell stat-icon"></i>
                <div class="stat-number">{{ $stats['total_bookings'] }}</div>
                <div>Tổng lịch đặt</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <i class="fas fa-check-circle stat-icon"></i>
                <div class="stat-number">{{ $stats['completed_classes'] }}</div>
                <div>Đã hoàn thành</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                <i class="fas fa-calendar-alt stat-icon"></i>
                <div class="stat-number">{{ $stats['upcoming_bookings'] }}</div>
                <div>Sắp tới</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                <i class="fas fa-fire stat-icon"></i>
                <div class="stat-number">{{ $member->daysUntilExpiry() > 0 ? $member->daysUntilExpiry() : 0 }}</div>
                <div>Ngày còn lại</div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Left Column --}}
        <div class="col-lg-8">
            {{-- Tabs --}}
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#info">
                        <i class="fas fa-user"></i> Thông tin
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#bookings">
                        <i class="fas fa-calendar"></i> Lịch học
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#health">
                        <i class="fas fa-heartbeat"></i> Sức khỏe
                    </a>
                </li>
            </ul>

            {{-- Tab Content --}}
            <div class="tab-content">
                {{-- Info Tab --}}
                <div class="tab-pane fade show active" id="info">
                    <div class="info-section">
                        <h5 class="fw-bold mb-4"><i class="fas fa-id-card"></i> Thông tin cá nhân</h5>
                        
                        <div class="info-row">
                            <span class="text-muted">Họ tên:</span>
                            <span class="fw-bold">{{ $member->name }}</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="text-muted">Email:</span>
                            <span>{{ $member->email }}</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="text-muted">Số điện thoại:</span>
                            <span>{{ $member->phone ?: 'Chưa cập nhật' }}</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="text-muted">Ngày sinh:</span>
                            <span>{{ $member->date_of_birth ? $member->date_of_birth->format('d/m/Y') : 'Chưa cập nhật' }}</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="text-muted">Giới tính:</span>
                            <span>
                                @if($member->gender === 'male') Nam
                                @elseif($member->gender === 'female') Nữ
                                @elseif($member->gender === 'other') Khác
                                @else Chưa cập nhật
                                @endif
                            </span>
                        </div>
                        
                        <div class="info-row">
                            <span class="text-muted">Địa chỉ:</span>
                            <span>{{ $member->address ?: 'Chưa cập nhật' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Bookings Tab --}}
                <div class="tab-pane fade" id="bookings">
                    <div class="info-section">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0"><i class="fas fa-calendar-check"></i> Lịch học gần đây</h5>
                            <a href="{{ route('bookings.my') }}" class="btn btn-outline-primary btn-sm">
                                Xem tất cả
                            </a>
                        </div>

                        @forelse($member->bookings()->latest()->take(5)->get() as $booking)
                            <div class="booking-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $booking->gymClass->name }}</h6>
                                        <p class="text-muted mb-1 small">
                                            <i class="fas fa-user-tie"></i> {{ $booking->gymClass->trainer->name }}
                                        </p>
                                        <p class="mb-0 small">
                                            <i class="fas fa-calendar"></i> {{ $booking->booking_date->format('d/m/Y') }}
                                            <span class="mx-2">|</span>
                                            <i class="fas fa-clock"></i> {{ $booking->booking_time ? $booking->booking_time->format('H:i') : $booking->gymClass->start_time->format('H:i') }}
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge status-{{ $booking->status }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                        <div class="mt-2">
                                            <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary">
                                                Chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                                <p>Chưa có lịch học nào</p>
                                <a href="{{ route('classes.index') }}" class="btn btn-primary">
                                    Đặt lịch ngay
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Health Tab --}}
                <div class="tab-pane fade" id="health">
                    <div class="info-section">
                        <h5 class="fw-bold mb-4"><i class="fas fa-heartbeat"></i> Chỉ số sức khỏe</h5>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="health-metric">
                                    <i class="fas fa-ruler-vertical fa-2x text-primary mb-2"></i>
                                    <h3 class="fw-bold mb-1">{{ $member->height ?: '---' }}</h3>
                                    <p class="text-muted mb-0">Chiều cao (cm)</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="health-metric">
                                    <i class="fas fa-weight fa-2x text-success mb-2"></i>
                                    <h3 class="fw-bold mb-1">{{ $member->weight ?: '---' }}</h3>
                                    <p class="text-muted mb-0">Cân nặng (kg)</p>
                                </div>
                            </div>
                        </div>

                        @if($member->height && $member->weight)
                            @php
                                $heightInMeters = $member->height / 100;
                                $bmi = round($member->weight / ($heightInMeters * $heightInMeters), 1);
                                
                                if ($bmi < 18.5) {
                                    $bmiStatus = 'Thiếu cân';
                                    $bmiColor = 'warning';
                                } elseif ($bmi < 25) {
                                    $bmiStatus = 'Bình thường';
                                    $bmiColor = 'success';
                                } elseif ($bmi < 30) {
                                    $bmiStatus = 'Thừa cân';
                                    $bmiColor = 'warning';
                                } else {
                                    $bmiStatus = 'Béo phì';
                                    $bmiColor = 'danger';
                                }
                            @endphp

                            <div class="alert alert-{{ $bmiColor }} text-center">
                                <h4 class="fw-bold mb-2">BMI: {{ $bmi }}</h4>
                                <p class="mb-0">{{ $bmiStatus }}</p>
                            </div>
                        @endif

                        @if($member->health_notes)
                            <div class="mt-4">
                                <h6 class="fw-bold mb-2">Ghi chú sức khỏe:</h6>
                                <div class="alert alert-info">
                                    {{ $member->health_notes }}
                                </div>
                            </div>
                        @endif

                        <div class="text-center mt-4">
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="fas fa-edit"></i> Cập nhật thông tin
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            {{-- Membership Card --}}
            <div class="membership-card {{ $member->membership_type }}">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="mb-1">Thẻ thành viên</h6>
                        <h3 class="fw-bold mb-0">{{ strtoupper($member->membership_type) }}</h3>
                    </div>
                    <i class="fas fa-id-card fa-3x opacity-50"></i>
                </div>

                <hr style="border-color: rgba(255,255,255,0.3);">

                <div class="mb-2">
                    <small>Ngày bắt đầu</small>
                    <div class="fw-bold">{{ $member->membership_start->format('d/m/Y') }}</div>
                </div>

                <div class="mb-3">
                    <small>Ngày hết hạn</small>
                    <div class="fw-bold">{{ $member->membership_end->format('d/m/Y') }}</div>
                </div>

                @if($member->isActive())
                    <div class="progress" style="height: 5px; background: rgba(255,255,255,0.3);">
                        @php
                            $totalDays = $member->membership_start->diffInDays($member->membership_end);
                            $daysUsed = $member->membership_start->diffInDays(now());
                            $percentage = ($daysUsed / $totalDays) * 100;
                        @endphp
                        <div class="progress-bar bg-white" style="width: {{ $percentage }}%"></div>
                    </div>
                    <small class="mt-2 d-block">Còn {{ $member->daysUntilExpiry() }} ngày</small>
                @else
                    <div class="alert alert-danger text-center mb-0">
                        <i class="fas fa-exclamation-triangle"></i> Thẻ đã hết hạn
                    </div>
                @endif

                <button class="btn btn-light w-100 mt-3" data-bs-toggle="modal" data-bs-target="#renewModal">
                    <i class="fas fa-sync-alt"></i> Gia hạn thẻ
                </button>
            </div>

            {{-- Quick Actions --}}
            <div class="info-section">
                <h6 class="fw-bold mb-3"><i class="fas fa-bolt"></i> Thao tác nhanh</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('classes.index') }}" class="btn btn-primary">
                        <i class="fas fa-calendar-plus"></i> Đặt lịch học
                    </a>
                    <a href="{{ route('bookings.my') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list"></i> Xem lịch của tôi
                    </a>
                    <a href="{{ route('classes.schedule') }}" class="btn btn-outline-info">
                        <i class="fas fa-calendar-week"></i> Lịch trong tuần
                    </a>
                    <a href="{{ route('trainers.index') }}" class="btn btn-outline-success">
                        <i class="fas fa-user-tie"></i> Xem huấn luyện viên
                    </a>
                </div>
            </div>

            {{-- Support --}}
            <div class="alert alert-info">
                <h6 class="fw-bold"><i class="fas fa-question-circle"></i> Cần hỗ trợ?</h6>
                <p class="mb-2 small">Liên hệ với chúng tôi:</p>
                <p class="mb-1"><i class="fas fa-phone"></i> 0123 456 789</p>
                <p class="mb-0"><i class="fas fa-envelope"></i> support@gymfitness.vn</p>
            </div>
        </div>
    </div>
</div>

{{-- Edit Profile Modal --}}
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Chỉnh sửa hồ sơ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $member->name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ $member->email }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" value="{{ $member->phone }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Ngày sinh</label>
                            <input type="date" name="date_of_birth" class="form-control" value="{{ $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : '' }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Giới tính</label>
                            <select name="gender" class="form-select">
                                <option value="">Chọn giới tính</option>
                                <option value="male" {{ $member->gender === 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ $member->gender === 'female' ? 'selected' : '' }}>Nữ</option>
                                <option value="other" {{ $member->gender === 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Ảnh đại diện</label>
                            <input type="file" name="avatar" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Địa chỉ</label>
                        <textarea name="address" class="form-control" rows="2">{{ $member->address }}</textarea>
                    </div>

                    <hr>
                    <h6 class="fw-bold mb-3">Thông tin sức khỏe</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Chiều cao (cm)</label>
                            <input type="number" name="height" class="form-control" value="{{ $member->height }}" step="0.1" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Cân nặng (kg)</label>
                            <input type="number" name="weight" class="form-control" value="{{ $member->weight }}" step="0.1" min="0">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ghi chú sức khỏe</label>
                        <textarea name="health_notes" class="form-control" rows="3" placeholder="Vấn đề sức khỏe, chấn thương, dị ứng...">{{ $member->health_notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Renew Membership Modal --}}
<div class="modal fade" id="renewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Gia hạn thẻ thành viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('profile.renew') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Loại thẻ <span class="text-danger">*</span></label>
                        <select name="membership_type" class="form-select" id="membershipType" required>
                            <option value="basic">Basic - 500,000 ₫/tháng</option>
                            <option value="premium">Premium - 1,000,000 ₫/tháng</option>
                            <option value="vip">VIP - 2,000,000 ₫/tháng</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Số tháng <span class="text-danger">*</span></label>
                        <input type="number" name="duration_months" class="form-control" id="durationMonths" min="1" max="12" value="1" required>
                        <small class="text-muted">Tối đa 12 tháng</small>
                    </div>

                    <div class="alert alert-info">
                        <h6 class="fw-bold mb-2">Tổng thanh toán:</h6>
                        <h3 class="mb-0" id="totalPrice">500,000 ₫</h3>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i>
                        <strong>Lưu ý:</strong> Thời gian sẽ được cộng dồn vào thẻ hiện tại của bạn.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Xác nhận gia hạn</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Calculate total price
const membershipType = document.getElementById('membershipType');
const durationMonths = document.getElementById('durationMonths');
const totalPrice = document.getElementById('totalPrice');

const prices = {
    'basic': 500000,
    'premium': 1000000,
    'vip': 2000000
};

function updateTotalPrice() {
    const type = membershipType.value;
    const months = parseInt(durationMonths.value) || 1;
    const total = prices[type] * months;
    totalPrice.textContent = total.toLocaleString('vi-VN') + ' ₫';
}

membershipType.addEventListener('change', updateTotalPrice);
durationMonths.addEventListener('input', updateTotalPrice);
</script>
@endpush
@endsection