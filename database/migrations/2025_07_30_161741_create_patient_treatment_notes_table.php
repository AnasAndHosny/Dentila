<?php

use App\Models\Patient;
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
        Schema::create('patient_treatment_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Patient::class)
                ->constrained('patients')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('title');
            $table->longText('text');
            $table->date('until_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_treatment_notes');
    }
};
