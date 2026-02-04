{{-- ============================================ --}}
{{-- 10. resources/views/trainers/index.blade.php --}}
{{-- ============================================ --}}

@extends('layouts.app')

@section('title', 'Huấn luyện viên - Gym Fitness')

@push('styles')
<style>
    .trainers-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4rem 0;
        margin-bottom: 2rem;
    }

    .trainer-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: all 0.3s;
        margin-bottom: 2rem;
        height: 100%;
    }

    .trainer-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }

    .trainer-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
        position: relative;
    }

    .trainer-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255,255,255,0.95);
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: bold;
        color: #667eea;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    .trainer-info {
        padding: 2rem;
    }

    .trainer-name {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .trainer-specialization {
        color: #667eea;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .trainer-stats {
        display: flex;
        justify-content: space-around;
        padding: 1rem 0;
        border-top: 1px solid #f0f0f0;
        border-bottom: 1px solid #f0f0f0;
        margin: 1rem 0;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 1.3rem;
        font-weight: bold;
        color: #667eea;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #666;
    }

    .social-links a {
        display: inline-block;
        width: 35px;
        height: 35px;
        line-height: 35px;
        text-align: center;
        border-radius: 50%;
        background: #f0f0f0;
        color: #666;
        margin-right: 8px;
        transition: all 0.3s;
    }

    .social-links a:hover {
        background: #667eea;
        color: white;
        transform: scale(1.1);
    }

    .filter-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }

    .achievement-badge {
        display: inline-block;
        padding: 5px 12px;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border-radius: 15px;
        font-size: 0.8rem;
        margin: 3px;
    }
</style>
@endpush

@section('content')
{{-- Header --}}
<div class="trainers-header">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">
            <i class="fas fa-user-tie"></i> Huấn luyện viên của chúng tôi
        </h1>
        <p class="lead">Đội ngũ HLV chuyên nghiệp, giàu kinh nghiệm và tận tâm</p>
    </div>
</div>

<div class="container mb-5">
    {{-- Filter Section --}}
    <div class="filter-card">
        <form action="{{ route('trainers.index') }}" method="GET">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Tìm kiếm HLV..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="specialization" class="form-select">
                        <option value="">Tất cả chuyên môn</option>
                        <option value="Yoga" {{ request('specialization') == 'Yoga' ? 'selected' : '' }}>Yoga</option>
                        <option value="Cardio" {{ request('specialization') == 'Cardio' ? 'selected' : '' }}>Cardio</option>
                        <option value="Strength" {{ request('specialization') == 'Strength' ? 'selected' : '' }}>Strength</option>
                        <option value="Boxing" {{ request('specialization') == 'Boxing' ? 'selected' : '' }}>Boxing</option>
                        <option value="CrossFit" {{ request('specialization') == 'CrossFit' ? 'selected' : '' }}>CrossFit</option>
                        <option value="Pilates" {{ request('specialization') == 'Pilates' ? 'selected' : '' }}>Pilates</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="sort" class="form-select">
                        <option value="rating">Đánh giá cao nhất</option>
                        <option value="experience" {{ request('sort') == 'experience' ? 'selected' : '' }}>Kinh nghiệm</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Trainers Grid --}}
    <div class="row">
        @forelse($trainers as $trainer)
        <div class="col-lg-4 col-md-6">
            <div class="card trainer-card">
                <div style="position: relative;">
                    <img src="{{ $trainer->avatar_url }}" alt="{{ $trainer->name }}" class="trainer-image">
                    @if($trainer->rating >= 4.5)
                        <div class="trainer-badge">
                            <i class="fas fa-star text-warning"></i> Top Rated
                        </div>
                    @endif
                </div>
                
                <div class="trainer-info">
                    <h3 class="trainer-name">{{ $trainer->name }}</h3>
                    <p class="trainer-specialization">
                        <i class="fas fa-dumbbell"></i> {{ $trainer->specialization }}
                    </p>

                    <div class="mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $trainer->rating ? 'text-warning' : 'text-muted' }}"></i>
                        @endfor
                        <span class="ms-2">{{ number_format($trainer->rating, 1) }}</span>
                        <span class="text-muted">({{ $trainer->total_reviews }} đánh giá)</span>
                    </div>

                    <div class="trainer-stats">
                        <div class="stat-item">
                            <div class="stat-number">{{ $trainer->experience_years }}</div>
                            <div class="stat-label">Năm KN</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $trainer->activeClasses->count() }}</div>
                            <div class="stat-label">Lớp học</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">
                                @php
                                    $studentCount = \App\Models\Booking::whereHas('gymClass', function($q) use ($trainer) {
                                        $q->where('trainer_id', $trainer->id);
                                    })->where('status', 'completed')->distinct('member_id')->count('member_id');
                                @endphp
                                {{ $studentCount }}+
                            </div>
                            <div class="stat-label">Học viên</div>
                        </div>
                    </div>

                    @if($trainer->bio)
                        <p class="text-muted small mb-3">{{ Str::limit($trainer->bio, 100) }}</p>
                    @endif

                    {{-- Social Links --}}
                    @if($trainer->facebook || $trainer->instagram || $trainer->youtube)
                    <div class="social-links mb-3">
                        @if($trainer->facebook)
                            <a href="{{ $trainer->facebook }}" target="_blank">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        @endif
                        @if($trainer->instagram)
                            <a href="{{ $trainer->instagram }}" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif
                        @if($trainer->youtube)
                            <a href="{{ $trainer->youtube }}" target="_blank">
                                <i class="fab fa-youtube"></i>
                            </a>
                        @endif
                    </div>
                    @endif

                    <div class="d-grid">
                        <a href="{{ route('trainers.show', $trainer->id) }}" class="btn btn-primary">
                            <i class="fas fa-user-circle"></i> Xem hồ sơ
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-user-slash fa-4x text-muted mb-3"></i>
            <h3>Không tìm thấy huấn luyện viên nào</h3>
            <a href="{{ route('trainers.index') }}" class="btn btn-primary mt-3">Xem tất cả</a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($trainers->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $trainers->links() }}
        </div>
    @endif
</div>
@endsection