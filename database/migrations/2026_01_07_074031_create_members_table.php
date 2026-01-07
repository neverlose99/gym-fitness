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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            
            // Thông tin thành viên
            $table->enum('membership_type', ['basic', 'premium', 'vip'])->default('basic');
            $table->date('membership_start');
            $table->date('membership_end');
            $table->decimal('membership_price', 10, 2)->default(0);
            
            // Thông tin sức khỏe (tùy chọn)
            $table->decimal('height', 5, 2)->nullable()->comment('Chiều cao (cm)');
            $table->decimal('weight', 5, 2)->nullable()->comment('Cân nặng (kg)');
            $table->text('health_notes')->nullable()->comment('Ghi chú sức khỏe');
            
            // Trạng thái
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            
            // Avatar/Photo
            $table->string('avatar')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // Soft delete để lưu lịch sử
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};