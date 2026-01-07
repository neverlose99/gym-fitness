{{-- ============================================ --}}
{{-- 3. resources/views/classes/index.blade.php --}}
{{-- ============================================ --}}

@extends('layouts.app')

@section('title', 'Danh sách lớp học - Gym Fitness')

@section('content')
<div class="container my-5">
    <h1 class="display-4 fw-bold text-center mb-4">Lớp học của chúng tôi</h1>
    
    {{-- Filter Section --}}
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('classes.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Tìm kiếm..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="level" class="form-select">
                            <option value="">Tất cả cấp độ</option>
                            @foreach($levels as $level)
                                <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>
                                    {{ ucfirst($level) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="category" class="form-select">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="trainer_id" class="form-select">
                            <option value="">Tất cả HLV</option>
                            @foreach($trainers as $trainer)
                                <option value="{{ $trainer->id }}" {{ request('trainer_id') == $trainer->id ? 'selected' : '' }}>
                                    {{ $trainer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="sort" class="form-select">
                            <option value="newest">Mới nhất</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Giá thấp</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Giá cao</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Phổ biến</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Classes Grid --}}
    <div class="row">
        @forelse($classes as $class)
        <div class="col-md-4 mb-4">
            <div class="card class-card h-100">
                <img src="{{ $class->image_url }}" class="card-img-top" alt="{{ $class->name }}">
                <div class="card-body class-card-body">
                    <span class="badge badge-{{ $class->level }}">{{ ucfirst($class->level) }}</span>
                    @if($class->is_featured)
                        <span class="badge bg-warning text-dark">Nổi bật</span>
                    @endif
                    
                    <h5 class="card-title mt-2">{{ $class->name }}</h5>
                    <p class="text-muted small">
                        <i class="fas fa-user-tie"></i> {{ $class->trainer->name }}
                    </p>
                    
                    <div class="mb-2">
                        <i class="fas fa-star text-warning"></i>
                        <span>{{ number_format($class->rating, 1) }}</span>
                        <span class="text-muted">({{ $class->total_reviews }})</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-clock"></i> {{ $class->duration }} phút</span>
                        <span><i class="fas fa-users"></i> {{ $class->availableSlots() }}/{{ $class->max_participants }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-primary mb-0">{{ number_format($class->price) }} ₫</h4>
                        <a href="{{ route('classes.show', $class->slug) }}" class="btn btn-primary">
                            Chi tiết
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-search fa-4x text-muted mb-3"></i>
            <h3>Không tìm thấy lớp học nào</h3>
            <a href="{{ route('classes.index') }}" class="btn btn-primary mt-3">Xem tất cả</a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $classes->links() }}
    </div>
</div>
@endsection