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
        Schema::create('treatment_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()
                ->constrained('employees')
                ->nullOnDelete();
            $table->foreignId('patient_treatment_id')->unique()
                ->constrained('patient_treatments')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('rating')->nullable()->default(null); // 1-5
            $table->text('comment')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_evaluations');
    }
};
