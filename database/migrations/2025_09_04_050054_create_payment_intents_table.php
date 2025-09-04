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
        Schema::create('payment_intents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_account_id')
                ->constrained('patient_accounts')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('stripe_payment_intent_id')->unique();
            $table->integer('amount');
            $table->string('currency', 10);
            $table->enum('status', ['pending','succeeded','failed','canceled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_intents');
    }
};
