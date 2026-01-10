{{-- ============================================ --}}
{{-- 6. resources/views/bookings/create.blade.php --}}
{{-- ============================================ --}}

@extends('layouts.app')

@section('title', 'Đặt lịch - ' . $class->name)

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Progress Steps --}}
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-4">
                        <div class="text-center flex-fill">
                            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2 mb-0 fw-bold">Chọn lớp</p>
                        </div>
                        <div class="flex-fill d-flex align-items-center">
                            <hr class="w-100 border-2">
                        </div>
                        <div class="text-center flex-fill">
                            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px;">
                                2
                            </div>
                            <p class="mt-2 mb-0 fw-bold text-primary">Thông tin</p>
                        </div>
                        <div class="flex-fill d-flex align-items-center">
                            <hr class="w-100 border-2 border-secondary">
                        </div>
                        <div class="text-center flex-fill">
                            <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px;">
                                3
                            </div>
                            <p class="mt-2 mb-0 text-muted">Xác nhận</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Class Info --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">Thông tin lớp học</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ $class->image_url }}" alt="{{ $class->name }}" 
                                 class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <h5 class="fw-bold">{{ $class->name }}</h5>
                            <p class="text-muted mb-2">
                                <i class="fas fa-user-tie"></i> HLV: {{ $class->trainer->name }}
                            </p>
                            <div class="mb-2">
                                <span class="badge badge-{{ $class->level }}">{{ ucfirst($class->level) }}</span>
                                <span class="badge bg-info text-dark">{{ ucfirst($class->category) }}</span>
                            </div>
                            <p class="mb-1">
                                <i class="fas fa-clock"></i> Thời lượng: {{ $class->duration }} phút
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-users"></i> Còn {{ $class->availableSlots() }} chỗ trống
                            </p>
                            <h4 class="text-primary mt-3 mb-0">{{ number_format($class->price) }} ₫</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Booking Form --}}
            <div class="card">
                <div class="card-body">
                    <h4 class="fw-bold mb-4">Thông tin đặt lịch</h4>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ $class->id }}">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Ngày học <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="booking_date" class="form-control" 
                                       min="{{ date('Y-m-d') }}" 
                                       value="{{ old('booking_date') }}"
                                       required>
                                <small class="text-muted">Chọn ngày bạn muốn tham gia</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Giờ học
                                </label>
                                <input type="time" name="booking_time" class="form-control" 
                                       value="{{ $class->start_time->format('H:i') }}" readonly>
                                <small class="text-muted">Giờ bắt đầu lớp học</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                Ngày trong tuần
                            </label>
                            <div>
                                @foreach($class->days_of_week as $day)
                                    <span class="badge bg-primary me-2">{{ ucfirst($day) }}</span>
                                @endforeach
                            </div>
                            <small class="text-muted">Lớp học diễn ra vào các ngày này trong tuần</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                Ghi chú (Tùy chọn)
                            </label>
                            <textarea name="member_notes" class="form-control" rows="4" 
                                      placeholder="Bạn có điều gì muốn HLV biết? (vấn đề sức khỏe, mục tiêu tập luyện...)">{{ old('member_notes') }}</textarea>
                        </div>

                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3">Chi tiết thanh toán</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Giá lớp học:</span>
                                    <span class="fw-bold">{{ number_format($class->price) }} ₫</span>
                                </div>
                                @if($class->package_price)
                                    <div class="alert alert-info mb-0 mt-3">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Ưu đãi:</strong> Mua gói tháng chỉ {{ number_format($class->package_price) }} ₫
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                            <label class="form-check-label" for="agreeTerms">
                                Tôi đã đọc và đồng ý với 
                                <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">điều khoản sử dụng</a>
                                <span class="text-danger">*</span>
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-calendar-check"></i> Xác nhận đặt lịch
                            </button>
                            <a href="{{ route('classes.show', $class->slug) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Important Notes --}}
            <div class="alert alert-warning mt-4">
                <h6 class="fw-bold"><i class="fas fa-exclamation-triangle"></i> Lưu ý quan trọng:</h6>
                <ul class="mb-0">
                    <li>Vui lòng đến trước giờ học 10 phút để check-in</li>
                    <li>Mang theo đồ tập phù hợp và nước uống</li>
                    <li>Có thể hủy lịch trước 24 giờ để được hoàn tiền</li>
                    <li>Liên hệ hotline nếu cần hỗ trợ: 0123 456 789</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Terms Modal --}}
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Điều khoản sử dụng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6 class="fw-bold">1. Chính sách đặt lịch</h6>
                <p>Khách hàng có thể đặt lịch trước tối thiểu 1 giờ và tối đa 30 ngày.</p>
                
                <h6 class="fw-bold">2. Chính sách hủy lịch</h6>
                <p>Hủy trước 24 giờ: Hoàn 100% phí. Hủy trong vòng 24 giờ: Không hoàn phí.</p>
                
                <h6 class="fw-bold">3. Quy định tham gia</h6>
                <p>Vui lòng đến đúng giờ, mang theo đồ tập phù hợp và tuân thủ hướng dẫn của HLV.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đồng ý</button>
            </div>
        </div>
    </div>
</div>
@endsection