<?php

use App\Models\Employee;
use App\Models\PatientAccount;
use App\Models\PatientTreatment;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PatientAccount::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(PatientTreatment::class)->nullable()->default(null)
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('created_by')
                ->constrained('employees')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->enum('type', ['debit', 'credit']);
            $table->unsignedInteger('amount');
            $table->enum('method', ['manual', 'electronic'])->default('manual');
            $table->string('note')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
