<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            
            // Thông tin lớp học
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('benefits')->nullable()->comment('Lợi ích của lớp học');
            
            // Huấn luyện viên
            $table->foreignId('trainer_id')->constrained('trainers')->onDelete('cascade');
            
            // Phân loại
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->enum('category', ['yoga', 'cardio', 'strength', 'boxing', 'dance', 'crossfit', 'pilates', 'other'])->default('other');
            
            // Thời gian
            $table->integer('duration')->comment('Thời lượng (phút)');
            $table->time('start_time')->comment('Giờ bắt đầu');
            $table->time('end_time')->nullable()->comment('Giờ kết thúc (tự động tính)');
            $table->json('days_of_week')->comment('Các ngày trong tuần: ["monday", "wednesday", "friday"]');
            
            // Ngày có hiệu lực
            $table->date('start_date')->nullable()->comment('Ngày bắt đầu lớp');
            $table->date('end_date')->nullable()->comment('Ngày kết thúc lớp');
            
            // Số lượng
            $table->integer('max_participants')->default(20);
            $table->integer('min_participants')->default(5)->comment('Số người tối thiểu để mở lớp');
            $table->integer('current_participants')->default(0)->comment('Số người đã đăng ký');
            
            // Giá cả
            $table->decimal('price', 10, 2)->comment('Giá 1 buổi');
            $table->decimal('package_price', 10, 2)->nullable()->comment('Giá gói tháng');
            $table->boolean('is_free_trial')->default(false)->comment('Có học thử miễn phí không');
            
            // Địa điểm
            $table->string('room')->nullable()->comment('Phòng tập');
            $table->string('location')->nullable()->comment('Vị trí cụ thể');
            
            // Media
            $table->string('image')->nullable();
            $table->string('video_url')->nullable()->comment('Video giới thiệu');
            $table->json('gallery')->nullable()->comment('Album ảnh');
            
            // Yêu cầu
            $table->text('requirements')->nullable()->comment('Yêu cầu (dụng cụ, trang phục...)');
            $table->integer('calories_burn')->nullable()->comment('Lượng calo tiêu hao trung bình');
            
            // Trạng thái
            $table->enum('status', ['active', 'inactive', 'full', 'cancelled'])->default('active');
            $table->boolean('is_featured')->default(false)->comment('Lớp nổi bật');
            $table->boolean('is_online')->default(false)->comment('Lớp online');
            
            // Đánh giá
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes để tăng tốc truy vấn
            $table->index('trainer_id');
            $table->index('level');
            $table->index('category');
            $table->index('status');
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};