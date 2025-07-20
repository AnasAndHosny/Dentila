<?php

use App\Models\PatientTreatmentStep;
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
        Schema::create('patient_treatment_substeps', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PatientTreatmentStep::class)
                ->constrained('patient_treatment_steps')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('name');
            $table->integer('queue')->default(0);
            $table->boolean('optional')->default(false);
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
        Schema::dropIfExists('patient_treatment_substeps');
    }
};
