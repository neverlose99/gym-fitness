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
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            
            // Thông tin cơ bản
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('male');
            $table->text('address')->nullable();
            
            // Thông tin chuyên môn
            $table->string('specialization')->comment('Chuyên môn (Yoga, Boxing, Cardio, ...)');
            $table->text('bio')->nullable()->comment('Tiểu sử, giới thiệu');
            $table->integer('experience_years')->default(0)->comment('Số năm kinh nghiệm');
            
            // Chứng chỉ và bằng cấp
            $table->json('certifications')->nullable()->comment('Các chứng chỉ');
            $table->text('achievements')->nullable()->comment('Thành tích');
            
            // Lương và hoa hồng
            $table->decimal('base_salary', 10, 2)->default(0)->comment('Lương cơ bản');
            $table->decimal('commission_rate', 5, 2)->default(0)->comment('Tỷ lệ hoa hồng (%)');
            
            // Thông tin làm việc
            $table->date('hire_date')->nullable()->comment('Ngày bắt đầu làm');
            $table->json('working_days')->nullable()->comment('Ngày làm việc trong tuần');
            $table->time('shift_start')->nullable()->comment('Giờ bắt đầu ca');
            $table->time('shift_end')->nullable()->comment('Giờ kết thúc ca');
            
            // Media
            $table->string('avatar')->nullable();
            $table->json('gallery')->nullable()->comment('Album ảnh');
            
            // Social media
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            
            // Đánh giá
            $table->decimal('rating', 3, 2)->default(0)->comment('Đánh giá trung bình (0-5)');
            $table->integer('total_reviews')->default(0)->comment('Tổng số đánh giá');
            
            // Trạng thái
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
};