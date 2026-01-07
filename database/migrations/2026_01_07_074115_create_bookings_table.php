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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            
            // Liên kết với thành viên và lớp học
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            
            // Thông tin đặt lịch
            $table->date('booking_date');
            $table->time('booking_time')->nullable();
            $table->string('booking_code')->unique()->comment('Mã đặt lịch');
            
            // Thanh toán
            $table->decimal('price', 10, 2)->comment('Giá tại thời điểm đặt');
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->enum('payment_method', ['cash', 'card', 'transfer', 'e-wallet'])->nullable();
            $table->dateTime('payment_date')->nullable();
            
            // Trạng thái
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed', 'no_show'])->default('confirmed');
            $table->text('cancellation_reason')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            
            // Ghi chú
            $table->text('member_notes')->nullable()->comment('Ghi chú từ thành viên');
            $table->text('admin_notes')->nullable()->comment('Ghi chú từ admin');
            
            // Điểm danh
            $table->boolean('is_checked_in')->default(false);
            $table->dateTime('checked_in_at')->nullable();
            
            // Đánh giá sau khi tham gia
            $table->integer('rating')->nullable()->comment('Đánh giá 1-5 sao');
            $table->text('review')->nullable()->comment('Nhận xét');
            $table->dateTime('reviewed_at')->nullable();
            
            // Nhắc nhở
            $table->boolean('reminder_sent')->default(false);
            $table->dateTime('reminder_sent_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['member_id', 'booking_date']);
            $table->index('class_id');
            $table->index('booking_code');
            $table->index('status');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};