<?php

use App\Models\Category;
use App\Models\Patient;
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
        Schema::create('patient_treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Patient::class)
                ->constrained('patients')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('name');
            $table->foreignIdFor(Category::class)
                ->constrained('categories')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->unsignedInteger('cost');
            $table->foreignIdFor(ToothStatus::class)->nullable()->default(null)
                ->constrained('tooth_statuses')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('main_complaint')->nullable()->default(null);
            $table->string('diagnoses')->nullable()->default(null);
            $table->boolean('finished')->default(value: false);
            $table->unsignedTinyInteger('complete_percentage')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_treatments');
    }
};
