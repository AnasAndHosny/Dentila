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
        Schema::create('patient_diseases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('disease_id')
                ->constrained('diseases')
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
        Schema::dropIfExists('patient_diseases');
    }
};
