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
        Schema::create('treatment_substeps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_step_id')
                ->constrained('treatment_steps')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('name');
            $table->integer('queue')->default(0);
            $table->boolean('optional')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_substeps');
    }
};
