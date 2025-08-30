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
        Schema::create('queue_turns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete(); // الطبيب
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete(); // ممكن يكون null
            $table->foreignId('queue_turn_status_id')->constrained()->cascadeOnDelete(); // حالة الدور
            $table->timestamp('arrival_time')->useCurrent(); // وقت وصول المريض
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_turns');
    }
};
