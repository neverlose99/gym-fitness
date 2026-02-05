{{-- ============================================ --}}
{{-- 2. resources/views/admin/dashboard.blade.php --}}
{{-- ============================================ --}}

@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- Stats Cards --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Thành viên</div>
                    <h3 class="mb-0">{{ $stats['total_members'] }}</h3>
                    <small class="text-success">{{ $stats['active_members'] }} đang hoạt động</small>
                </div>
                <i class="fas fa-users fa-3x opacity-25"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Huấn luyện viên</div>
                    <h3 class="mb-0">{{ $stats['total_trainers'] }}</h3>
                    <small class="text-success">{{ $stats['active_trainers'] }} đang hoạt động</small>
                </div>
                <i class="fas fa-user-tie fa-3x opacity-25"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Lớp học</div>
                    <h3 class="mb-0">{{ $stats['total_classes'] }}</h3>
                    <small class="text-success">{{ $stats['active_classes'] }} đang mở</small>
                </div>
                <i class="fas fa-dumbbell fa-3x opacity-25"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Đặt lịch hôm nay</div>
                    <h3 class="mb-0">{{ $stats['today_bookings'] }}</h3>
                    <small class="text-muted">Tổng: {{ $stats['total_bookings'] }}</small>
                </div>
                <i class="fas fa-calendar-check fa-3x opacity-25"></i>
            </div>
        </div>
    </div>
</div>

{{-- Revenue Cards --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="text-muted small">Doanh thu hôm nay</div>
            <h4 class="mb-0">{{ number_format($revenue['today']) }} ₫</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card info">
            <div class="text-muted small">Doanh thu tuần này</div>
            <h4 class="mb-0">{{ number_format($revenue['this_week']) }} ₫</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="text-muted small">Doanh thu tháng này</div>
            <h4 class="mb-0">{{ number_format($revenue['this_month']) }} ₫</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card primary">
            <div class="text-muted small">Doanh thu năm nay</div>
            <h4 class="mb-0">{{ number_format($revenue['this_year']) }} ₫</h4>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="row mb-4">
    <div class="col-md-6">
        <div class="table-responsive">
            <h5 class="mb-3">Đặt lịch theo tháng</h5>
            <div style="height:300px;">
                <canvas id="bookingsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="table-responsive">
            <h5 class="mb-3">Doanh thu theo tháng</h5>
            <div style="height:300px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Top Lists --}}
<div class="row mb-4">
    <div class="col-md-6">
        <div class="table-responsive">
            <h5 class="mb-3">Top 5 lớp học phổ biến</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Lớp học</th>
                        <th>HLV</th>
                        <th class="text-center">Bookings</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topClasses as $class)
                    <tr>
                        <td>{{ $class->name }}</td>
                        <td>{{ $class->trainer->name }}</td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $class->bookings_count }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="table-responsive">
            <h5 class="mb-3">Top 5 HLV đánh giá cao</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>HLV</th>
                        <th>Chuyên môn</th>
                        <th class="text-center">Rating</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topTrainers as $trainer)
                    <tr>
                        <td>{{ $trainer->name }}</td>
                        <td>{{ $trainer->specialization }}</td>
                        <td class="text-center">
                            <i class="fas fa-star text-warning"></i> {{ number_format($trainer->rating, 1) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Recent Bookings --}}
<div class="table-responsive mb-4">
    <h5 class="mb-3">Đặt lịch gần đây</h5>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Mã</th>
                <th>Thành viên</th>
                <th>Lớp học</th>
                <th>Ngày</th>
                <th>Giá</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentBookings as $booking)
            <tr>
                <td>{{ $booking->booking_code }}</td>
                <td>{{ $booking->member->name }}</td>
                <td>{{ $booking->gymClass->name }}</td>
                <td>{{ $booking->booking_date->format('d/m/Y') }}</td>
                <td>{{ number_format($booking->price) }} ₫</td>
                <td>
                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Expiring Members --}}
<div class="table-responsive">
    <h5 class="mb-3">Thành viên sắp hết hạn (30 ngày)</h5>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Tên</th>
                <th>Email</th>
                <th>Loại thẻ</th>
                <th>Ngày hết hạn</th>
                <th>Còn lại</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expiringMembers as $member)
            <tr>
                <td>{{ $member->name }}</td>
                <td>{{ $member->email }}</td>
                <td>
                    <span class="badge bg-{{ $member->membership_type === 'vip' ? 'danger' : ($member->membership_type === 'premium' ? 'warning' : 'info') }}">
                        {{ strtoupper($member->membership_type) }}
                    </span>
                </td>
                <td>{{ $member->membership_end->format('d/m/Y') }}</td>
                <td>
                    <span class="text-{{ $member->daysUntilExpiry() <= 7 ? 'danger' : 'warning' }}">
                        {{ $member->daysUntilExpiry() }} ngày
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.members.edit', $member->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i> Gia hạn
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
let bookingsChart = null;
let revenueChart = null;

document.addEventListener('DOMContentLoaded', function () {

    const bookingsCanvas = document.getElementById('bookingsChart');
    const revenueCanvas = document.getElementById('revenueChart');

    if (bookingsChart) bookingsChart.destroy();
    if (revenueChart) revenueChart.destroy();

    bookingsChart = new Chart(bookingsCanvas, {
        type: 'line',
        data: {
            labels: ['T1','T2','T3','T4','T5','T6','T7','T8','T9','T10','T11','T12'],
            datasets: [{
                label: 'Số lượng đặt lịch',
                data: [
                    @for($i = 1; $i <= 12; $i++)
                        {{ $bookingsByMonth[$i] ?? 0 }},
                    @endfor
                ],
                borderColor: '#4e73df',
                tension: 0.3,
                fill: true,
                backgroundColor: 'rgba(78, 115, 223, 0.1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    revenueChart = new Chart(revenueCanvas, {
        type: 'bar',
        data: {
            labels: ['T1','T2','T3','T4','T5','T6','T7','T8','T9','T10','T11','T12'],
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: [
                    @for($i = 1; $i <= 12; $i++)
                        {{ $revenueByMonth[$i] ?? 0 }},
                    @endfor
                ],
                backgroundColor: '#1cc88a'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

});
</script>
@endpush

@endsection