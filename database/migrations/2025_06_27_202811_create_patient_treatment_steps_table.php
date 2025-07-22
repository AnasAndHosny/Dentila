<?php

use App\Models\MedicationPlan;
use App\Models\PatientTreatment;
use App\Models\TreatmentNote;
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
        Schema::create('patient_treatment_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PatientTreatment::class)
                ->constrained('patient_treatments')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('name');
            $table->integer('queue')->default(0);
            $table->boolean('optional')->default(false);
            $table->foreignIdFor(TreatmentNote::class)->nullable()->default(null)
                ->constrained('treatment_notes')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignIdFor(MedicationPlan::class)->nullable()->default(null)
                ->constrained('medication_plans')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->boolean('finished')->default(value: false);
            $table->string('note')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_treatment_steps');
    }
};
