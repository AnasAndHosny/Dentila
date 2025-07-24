<?php

use App\Models\Patient;
use App\Models\Tooth;
use App\Models\ToothStatus;
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
        Schema::create('patient_teeth', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Patient::class)
                ->constrained('patients')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();;
            $table->foreignIdFor(Tooth::class)
                ->constrained('teeth')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(ToothStatus::class)->nullable()->default(null)
                ->constrained('tooth_statuses')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('note')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_teeth');
    }
};
