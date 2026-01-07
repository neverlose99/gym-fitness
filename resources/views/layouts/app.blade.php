{{-- ============================================ --}}
{{-- 1. resources/views/layouts/app.blade.php --}}
{{-- ============================================ --}}

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gym Fitness - Nơi thay đổi bản thân')</title>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #ff6b35;
            --secondary-color: #004e89;
            --dark-color: #1a1a2e;
            --light-color: #f5f5f5;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, var(--dark-color) 0%, var(--secondary-color) 100%);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: white !important;
        }

        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            margin: 0 0.5rem;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                        url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=1200') center/cover;
            height: 500px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .hero-section p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 12px 35px;
            font-size: 1.1rem;
            border-radius: 50px;
            transition: transform 0.3s;
        }

        .btn-primary:hover {
            background: #ff5722;
            transform: scale(1.05);
        }

        .class-card, .trainer-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 2rem;
        }

        .class-card:hover, .trainer-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .class-card img, .trainer-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .class-card-body, .trainer-card-body {
            padding: 1.5rem;
        }

        .badge-level {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .badge-beginner { background: #4caf50; }
        .badge-intermediate { background: #ff9800; }
        .badge-advanced { background: #f44336; }

        .stats-section {
            background: var(--light-color);
            padding: 4rem 0;
        }

        .stat-card {
            text-align: center;
            padding: 2rem;
        }

        .stat-card i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--secondary-color);
        }

        footer {
            background: var(--dark-color);
            color: white;
            padding: 3rem 0;
            margin-top: 4rem;
        }

        .alert {
            border-radius: 10px;
        }
    </style>

    @stack('styles')
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-dumbbell"></i> GYM FITNESS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('classes.index') }}">Lớp học</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('classes.schedule') }}">Lịch học</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('trainers.index') }}">Huấn luyện viên</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">Giới thiệu</a>
                    </li>
                    
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                               data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile.index') }}">Hồ sơ</a></li>
                                <li><a class="dropdown-item" href="{{ route('bookings.my') }}">Lịch của tôi</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Đăng xuất</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary text-white ms-2" href="{{ route('register') }}">
                                Đăng ký
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    @yield('content')

    {{-- Footer --}}
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-dumbbell"></i> GYM FITNESS</h5>
                    <p>Nơi bạn thay đổi bản thân và đạt được mục tiêu sức khỏe của mình.</p>
                </div>
                <div class="col-md-4">
                    <h5>Liên kết nhanh</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('classes.index') }}" class="text-white">Lớp học</a></li>
                        <li><a href="{{ route('trainers.index') }}" class="text-white">Huấn luyện viên</a></li>
                        <li><a href="{{ route('about') }}" class="text-white">Giới thiệu</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Liên hệ</h5>
                    <p><i class="fas fa-phone"></i> 0123 456 789</p>
                    <p><i class="fas fa-envelope"></i> info@gymfitness.vn</p>
                    <div class="mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <hr style="background: white;">
            <p class="text-center mb-0">&copy; 2025 Gym Fitness. All rights reserved.</p>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>