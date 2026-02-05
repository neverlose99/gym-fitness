{{-- ============================================ --}}
{{-- Trang Giới Thiệu - About Page --}}
{{-- ============================================ --}}

@extends('layouts.app')

@section('title', 'Dự Án Gym Fitness - Giới thiệu')

@push('styles')
<style>
    .about-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 6rem 0;
        text-align: center;
    }

    .about-hero h1 {
        font-size: 3.5rem;
        font-weight: bold;
        margin-bottom: 1.5rem;
    }

    .about-hero p {
        font-size: 1.2rem;
        max-width: 700px;
        margin: 0 auto;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 2rem;
        text-align: center;
        color: #333;
    }

    .section-title::after {
        content: '';
        display: block;
        width: 80px;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        margin: 1rem auto 0;
    }

    .value-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        transition: all 0.3s;
        height: 100%;
    }

    .value-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }

    .value-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #667eea;
    }

    .value-card h4 {
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .milestone-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s;
    }

    .milestone-card:hover {
        transform: scale(1.05);
    }

    .milestone-number {
        font-size: 2.5rem;
        font-weight: bold;
        display: block;
        margin-bottom: 0.5rem;
    }

    .milestone-label {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .team-member {
        text-align: center;
        margin-bottom: 2rem;
    }

    .team-member-img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 1rem;
        border: 4px solid #667eea;
    }

    .team-member h5 {
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .team-member p {
        color: #667eea;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .timeline {
        position: relative;
        padding: 2rem 0;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #667eea, #764ba2);
    }

    .timeline-item {
        margin-bottom: 3rem;
        width: 45%;
    }

    .timeline-item:nth-child(odd) {
        margin-left: 0;
        text-align: right;
        padding-right: 5%;
    }

    .timeline-item:nth-child(even) {
        margin-left: 55%;
        padding-left: 5%;
    }

    .timeline-content {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .timeline-year {
        font-weight: bold;
        color: #667eea;
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }

    @media (max-width: 768px) {
        .about-hero h1 {
            font-size: 2.5rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .timeline::before {
            left: 20px;
        }

        .timeline-item {
            width: 100%;
            padding-right: 0 !important;
            padding-left: 60px !important;
            margin-left: 0 !important;
            text-align: left !important;
        }

        .timeline-item:nth-child(odd),
        .timeline-item:nth-child(even) {
            width: 100%;
            padding-left: 60px;
        }
    }

    .cta-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4rem 0;
        text-align: center;
        border-radius: 15px;
        margin: 4rem 0;
    }

    .cta-section h2 {
        font-size: 2.5rem;
        margin-bottom: 1.5rem;
    }

    .btn-cta {
        background: white;
        color: #667eea;
        padding: 0.75rem 2rem;
        border-radius: 50px;
        font-weight: bold;
        transition: all 0.3s;
    }

    .btn-cta:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 20px rgba(255,255,255,0.3);
    }
</style>
@endpush

@section('content')

{{-- Hero Section --}}
<div class="about-hero">
    <div class="container">
        <h1>Dự Án Gym Fitness</h1>
        <p>Một dự án quản lý phòng tập gym được phát triển bởi nhóm sinh viên, giới thiệu một nền tảng hiện đại để quản lý thành viên, lớp học, và huấn luyện viên.</p>
    </div>
</div>

<div class="container my-5">

    {{-- Về Chúng Tôi --}}
    <section class="mb-5">
        <div class="row align-items-center">
            <div class="col-md-6">
                <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=600&h=400&fit=crop" alt="Gym Fitness" class="img-fluid rounded-3">
            </div>
            <div class="col-md-6">
                <h2 class="section-title">Về Dự Án</h2>
                <p class="lead">Dự án Gym Fitness được phát triển vào năm 2025 bởi nhóm sinh viên như một ứng dụng web quản lý phòng tập gym toàn diện, áp dụng các công nghệ web hiện đại.</p>
                
                <p>Dự án cung cấp giải pháp quản lý hoàn chỉnh bao gồm: quản lý thành viên, quản lý lớp học, quản lý huấn luyện viên, hệ thống đặt lịch tập, và dashboard quản trị viên.</p>
                
                <p>Từ giao diện người dùng thân thiện đến backend mạnh mẽ, dự án này thể hiện sự kết hợp hoàn hảo giữa thiết kế UX tuyệt vời và công nghệ phần mềm tiên tiến.</p>
            </div>
        </div>
    </section>

    {{-- Thống kê & Thành tựu --}}
    <section class="my-5">
        <h2 class="section-title mb-5">Thành Tựu Của Chúng Tôi</h2>
        <div class="row">
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="milestone-card">
                    <span class="milestone-number">2025</span>
                    <span class="milestone-label">Năm khởi động dự án</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="milestone-card">
                    <span class="milestone-number">2</span>
                    <span class="milestone-label">Thành viên nhóm</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="milestone-card">
                    <span class="milestone-number">Laravel</span>
                    <span class="milestone-label">Framework chính</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="milestone-card">
                    <span class="milestone-number">Full Stack</span>
                    <span class="milestone-label">Ứng dụng web</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Sứ Mệnh & Giá Trị --}}
    <section class="my-5">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h4>Mục Tiêu</h4>
                    <p>Phát triển một ứng dụng web quản lý gym đầy đủ chức năng, giúp các phòng tập gym hiện đại hóa quy trình quản lý và cung cấp trải nghiệm tốt hơn cho người dùng.</p>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>Tầm Nhìn</h4>
                    <p>Xây dựng một nền tảng quản lý gym toàn diện, minh bạch, dễ sử dụng, giúp gym quản lý hiệu quả và nâng cao sự hài lòng của thành viên.</p>
                </div>
            </div>
        </div>

        <h2 class="section-title mt-5 mb-4">Các Giá Trị Cốt Lõi</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <h4>Chất Lượng Code</h4>
                    <p>Viết code sạch, dễ bảo trì, theo các best practice và design pattern hiện đại trong development web.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h4>Học Hỏi & Đổi Mới</h4>
                    <p>Liên tục học tập các công nghệ mới, áp dụng best practice trong thiết kế hệ thống và phát triển ứng dụng.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4>Hợp Tác</h4>
                    <p>Làm việc theo nhóm một cách hiệu quả, chia sẻ kiến thức và giúp đỡ lẫn nhau để đạt được mục tiêu chung.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Lịch Sử Phát Triển --}}
    <section class="my-5">
        <h2 class="section-title mb-5">Hành Trình Dự Án</h2>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="timeline-year">Tháng 1-2 2025</div>
                    <h5>Khởi Động & Thiết Kế</h5>
                    <p>Lập kế hoạch dự án, thiết kế database, xác định các chức năng chính cần phát triển.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="timeline-year">Tháng 2-3 2025</div>
                    <h5>Phát Triển Backend</h5>
                    <p>Xây dựng API và logic business sử dụng Laravel, bao gồm authentication, CRUD cho các module chính.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="timeline-year">Tháng 3-4 2025</div>
                    <h5>Phát Triển Frontend</h5>
                    <p>Xây dựng giao diện người dùng sử dụng Blade template, Bootstrap, JavaScript để tạo trải nghiệm tốt nhất.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="timeline-year">Tháng 4-5 2025</div>
                    <h5>Kiểm Thử & Tối Ưu</h5>
                    <p>Kiểm thử toàn hệ thống, sửa bug, tối ưu hiệu năng, cải thiện UX/UI dựa trên feedback.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="timeline-year">Hiện Tại</div>
                    <h5>Hoàn Thiện & Giới Thiệu</h5>
                    <p>Hoàn thiện toàn bộ dự án, đối chiếu yêu cầu, chuẩn bị giới thiệu sản phẩm hoàn chỉnh.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Đội Thành Viên --}}
    <section class="my-5">
        <h2 class="section-title mb-5">Thành Viên Nhóm</h2>
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="team-member">
                    <img src="https://i.pravatar.cc/300?u=buiminhduc@gmail.com" alt="Bùi Minh Đức" class="team-member-img">
                    <h5>Bùi Minh Đức</h5>
                    <p>Full Stack Developer</p>
                    <p class="text-muted small">Sinh viên khoa Công Nghệ Thông Tin, chuyên về phát triển web sử dụng Laravel và modern frontend technologies. Chịu trách nhiệm phát triển toàn bộ hệ thống backend, APIs, database design, và giao diện admin.</p>
                </div>
            </div>
            <div class="col-md-5">
                <div class="team-member">
                    <img src="https://i.pravatar.cc/300?u=trinhthao@gmail.com" alt="Trịnh Thảo" class="team-member-img">
                    <h5>Trịnh Thảo</h5>
                    <p>Frontend Developer & UI/UX Designer</p>
                    <p class="text-muted small">Sinh viên khoa Công Nghệ Thông Tin, chuyên về thiết kế giao diện user-friendly và phát triển frontend. Chịu trách nhiệm thiết kế UX/UI, phát triển giao diện, đảm bảo trải nghiệm người dùng tốt nhất.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <div class="cta-section">
        <h2>Khám Phá Hệ Thống Quản Lý Gym Hoàn Chỉnh</h2>
        <p class="lead">Trải nghiệm ứng dụng web quản lý gym hiện đại do nhóm sinh viên phát triển</p>
        <a href="{{ route('classes.index') }}" class="btn btn-cta">Xem các Lớp Học</a>
    </div>

</div>

@endsection
