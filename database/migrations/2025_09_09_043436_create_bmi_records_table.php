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
        Schema::create('bmi_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->decimal('weight_kg', 5, 2);
            $table->unsignedSmallInteger('height_cm');
            $table->decimal('bmi', 5, 2);
            $table->string('bmi_category', 40);
            $table->json('diet_suggestion')->nullable();
            $table->json('routine_suggestion')->nullable();
            $table->timestamp('measured_at')->useCurrent();
            $table->timestamps();
            $table->index(['client_id', 'measured_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bmi_records');
    }
};
