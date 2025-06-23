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
        Schema::create('treatment_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_plan_id')
                ->constrained('treatment_plans')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('name');
            $table->integer('queue')->default(0);
            $table->boolean('optional')->default(false);
            $table->foreignId('treatment_note_id')->nullable()->default(null)
                ->constrained('treatment_notes')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('medication_plan_id')->nullable()->default(null)
                ->constrained('medication_plans')
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
        Schema::dropIfExists('treatment_steps');
    }
};
