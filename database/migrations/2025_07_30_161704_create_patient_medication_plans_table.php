<?php

use App\Models\Patient;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patient_medication_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Patient::class)
                ->constrained('patients')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('medication_id')
                ->constrained('medications')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('dose')->nullable();
            $table->date('until_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_medication_plans');
    }
};
