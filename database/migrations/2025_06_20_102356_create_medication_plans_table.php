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
        Schema::create('medication_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medication_id')
                ->constrained('medications')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('dose')->nullable();
            $table->unsignedInteger('duration_value');
            $table->enum('duration_unit', ['days', 'weeks', 'months']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_plans');
    }
};
