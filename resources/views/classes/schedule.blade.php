{{-- ============================================ --}}
{{-- 5. resources/views/classes/schedule.blade.php --}}
{{-- ============================================ --}}

@extends('layouts.app')

@section('title', 'Lịch học - Gym Fitness')

@push('styles')
<style>
    .schedule-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
    }

    .time-slot {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 10px;
        border-left: 4px solid var(--primary-color);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s;
        cursor: pointer;
    }

    .time-slot:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .day-column {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        min-height: 400px;
    }

    .day-header {
        font-size: 1.3rem;
        font-weight: bold;
        color: var(--secondary-color);
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 3px solid var(--primary-color);
    }

    .class-time {
        font-weight: bold;
        color: var(--primary-color);
        font-size: 1.1rem;
    }

    .class-name {
        font-weight: 600;
        margin: 5px 0;
    }

    .class-trainer {
        color: #666;
        font-size: 0.9rem;
    }

    .class-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 8px;
        font-size: 0.85rem;
    }

    .slots-badge {
        background: #e3f2fd;
        color: #1976d2;
        padding: 3px 10px;
        border-radius: 15px;
        font-weight: 600;
    }

    .slots-badge.limited {
        background: #fff3e0;
        color: #f57c00;
    }

    .slots-badge.full {
        background: #ffebee;
        color: #d32f2f;
    }

    .filter-section {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .legend {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .legend-color {
        width: 20px;
        height: 20px;
        border-radius: 5px;
    }

    @media (max-width: 768px) {
        .day-column {
            min-height: auto;
        }
    }
</style>
@endpush

@section('content')
{{-- Header --}}
<div class="schedule-header">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">
            <i class="fas fa-calendar-week"></i> Lịch học trong tuần
        </h1>
        <p class="lead">Xem lịch học và đặt lớp yêu thích của bạn</p>
    </div>
</div>

<div class="container mb-5">
    {{-- Filter Section --}}
    <div class="filter-section">
        <div class="row align-items-center">
            <div class="col-md-3">
                <select class="form-select" id="filterLevel">
                    <option value="">Tất cả cấp độ</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterCategory">
                    <option value="">Tất cả danh mục</option>
                    <option value="yoga">Yoga</option>
                    <option value="cardio">Cardio</option>
                    <option value="strength">Strength</option>
                    <option value="boxing">Boxing</option>
                    <option value="dance">Dance</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterTime">
                    <option value="">Tất cả thời gian</option>
                    <option value="morning">Sáng (6h-12h)</option>
                    <option value="afternoon">Chiều (12h-18h)</option>
                    <option value="evening">Tối (18h-22h)</option>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100" onclick="applyFilters()">
                    <i class="fas fa-filter"></i> Lọc
                </button>
            </div>
        </div>

        {{-- Legend --}}
        <div class="legend mt-3 pt-3 border-top">
            <div class="legend-item">
                <div class="legend-color" style="background: #4caf50;"></div>
                <small>Beginner</small>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #ff9800;"></div>
                <small>Intermediate</small>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #f44336;"></div>
                <small>Advanced</small>
            </div>
            <div class="legend-item">
                <i class="fas fa-check-circle text-success"></i>
                <small>Còn chỗ</small>
            </div>
            <div class="legend-item">
                <i class="fas fa-exclamation-circle text-warning"></i>
                <small>Sắp đầy</small>
            </div>
            <div class="legend-item">
                <i class="fas fa-times-circle text-danger"></i>
                <small>Đã đầy</small>
            </div>
        </div>
    </div>

    {{-- Schedule Grid --}}
    <div class="row">
        @php
            $daysOfWeek = [
                'monday' => 'Thứ 2',
                'tuesday' => 'Thứ 3',
                'wednesday' => 'Thứ 4',
                'thursday' => 'Thứ 5',
                'friday' => 'Thứ 6',
                'saturday' => 'Thứ 7',
                'sunday' => 'Chủ nhật'
            ];

            // Lấy tất cả lớp học active
            $allClasses = \App\Models\GymClass::with('trainer')->active()->get();
            
            // Organize classes by day
            $scheduleByDay = [];
            foreach($daysOfWeek as $day => $dayName) {
                $scheduleByDay[$day] = $allClasses->filter(function($class) use ($day) {
                    return in_array($day, $class->days_of_week ?? []);
                })->sortBy('start_time');
            }
        @endphp

        @foreach($daysOfWeek as $day => $dayName)
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="day-column" data-day="{{ $day }}">
                    <div class="day-header">
                        <i class="fas fa-calendar-day"></i> {{ $dayName }}
                    </div>
                    
                    @forelse($scheduleByDay[$day] as $class)
                        @php
                            $availableSlots = $class->availableSlots();
                            $slotsPercentage = ($availableSlots / $class->max_participants) * 100;
                            
                            if ($slotsPercentage > 30) {
                                $slotClass = '';
                                $slotIcon = 'check-circle text-success';
                            } elseif ($slotsPercentage > 0) {
                                $slotClass = 'limited';
                                $slotIcon = 'exclamation-circle text-warning';
                            } else {
                                $slotClass = 'full';
                                $slotIcon = 'times-circle text-danger';
                            }
                        @endphp

                        <div class="time-slot" 
                             data-level="{{ $class->level }}" 
                             data-category="{{ $class->category }}"
                             data-time="{{ $class->start_time->format('H') }}"
                             onclick="window.location.href='{{ route('classes.show', $class->slug) }}'">
                            
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="class-time">
                                    <i class="fas fa-clock"></i> {{ $class->start_time->format('H:i') }}
                                </div>
                                <span class="badge badge-{{ $class->level }}">
                                    {{ ucfirst($class->level) }}
                                </span>
                            </div>

                            <div class="class-name">{{ $class->name }}</div>
                            
                            <div class="class-trainer">
                                <i class="fas fa-user-tie"></i> {{ $class->trainer->name }}
                            </div>

                            <div class="class-info">
                                <span>
                                    <i class="fas fa-dumbbell"></i> {{ $class->duration }} phút
                                </span>
                                <span class="slots-badge {{ $slotClass }}">
                                    <i class="fas fa-{{ $slotIcon }}"></i>
                                    {{ $availableSlots }}/{{ $class->max_participants }}
                                </span>
                            </div>

                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt"></i> 
                                    {{ $class->room ?? 'TBA' }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-calendar-times fa-3x mb-3"></i>
                            <p>Không có lớp học</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    {{-- Quick Stats --}}
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-graduation-cap fa-3x text-primary mb-2"></i>
                    <h4 class="fw-bold">{{ $allClasses->count() }}</h4>
                    <p class="text-muted mb-0">Tổng lớp học</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-fire fa-3x text-danger mb-2"></i>
                    <h4 class="fw-bold">{{ $allClasses->where('level', 'beginner')->count() }}</h4>
                    <p class="text-muted mb-0">Lớp Beginner</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-bolt fa-3x text-warning mb-2"></i>
                    <h4 class="fw-bold">{{ $allClasses->where('level', 'intermediate')->count() }}</h4>
                    <p class="text-muted mb-0">Lớp Intermediate</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-trophy fa-3x text-success mb-2"></i>
                    <h4 class="fw-bold">{{ $allClasses->where('level', 'advanced')->count() }}</h4>
                    <p class="text-muted mb-0">Lớp Advanced</p>
                </div>
            </div>
        </div>
    </div>

    {{-- CTA Section --}}
    <div class="text-center mt-5 p-5 rounded" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <h3 class="text-white fw-bold mb-3">Bạn đã tìm thấy lớp học phù hợp?</h3>
        <p class="text-white mb-4">Đăng ký ngay để bắt đầu hành trình rèn luyện sức khỏe</p>
        @auth
            <a href="{{ route('classes.index') }}" class="btn btn-light btn-lg px-5">
                <i class="fas fa-calendar-plus"></i> Đặt lịch ngay
            </a>
        @else
            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">
                <i class="fas fa-user-plus"></i> Đăng ký ngay
            </a>
        @endguest
    </div>
</div>

@push('scripts')
<script>
function applyFilters() {
    const level = document.getElementById('filterLevel').value;
    const category = document.getElementById('filterCategory').value;
    const timeFilter = document.getElementById('filterTime').value;
    
    const timeSlots = document.querySelectorAll('.time-slot');
    
    timeSlots.forEach(slot => {
        let show = true;
        
        // Filter by level
        if (level && slot.dataset.level !== level) {
            show = false;
        }
        
        // Filter by category
        if (category && slot.dataset.category !== category) {
            show = false;
        }
        
        // Filter by time
        if (timeFilter) {
            const hour = parseInt(slot.dataset.time);
            if (timeFilter === 'morning' && (hour < 6 || hour >= 12)) {
                show = false;
            } else if (timeFilter === 'afternoon' && (hour < 12 || hour >= 18)) {
                show = false;
            } else if (timeFilter === 'evening' && (hour < 18 || hour >= 22)) {
                show = false;
            }
        }
        
        slot.style.display = show ? 'block' : 'none';
    });
    
    // Check if any day column is empty after filtering
    document.querySelectorAll('.day-column').forEach(column => {
        const visibleSlots = column.querySelectorAll('.time-slot[style="display: block;"], .time-slot:not([style*="display: none"])');
        const emptyMessage = column.querySelector('.text-center.py-5');
        
        if (visibleSlots.length === 0 && !emptyMessage) {
            const dayName = column.querySelector('.day-header').textContent;
            column.innerHTML += `
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-search fa-3x mb-3"></i>
                    <p>Không tìm thấy lớp học phù hợp</p>
                </div>
            `;
        }
    });
}

// Reset filters
function resetFilters() {
    document.getElementById('filterLevel').value = '';
    document.getElementById('filterCategory').value = '';
    document.getElementById('filterTime').value = '';
    
    document.querySelectorAll('.time-slot').forEach(slot => {
        slot.style.display = 'block';
    });
}
</script>
@endpush
@endsection