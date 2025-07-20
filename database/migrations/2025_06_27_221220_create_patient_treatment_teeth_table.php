<?php

use App\Models\PatientTooth;
use App\Models\PatientTreatment;
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
        Schema::create('patient_treatment_teeth', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PatientTreatment::class)
                ->constrained('patient_treatments')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(PatientTooth::class)
                ->constrained('patient_teeth')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_treatment_teeth');
    }
};
