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
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('meal_distribution_id')->constrained()->restrictOnDelete();
            $table->dateTime('incident_at');
            $table->unsignedInteger('affected_count')->default(1);
            $table->text('description');
            $table->string('evidence_path')->nullable();
            $table->string('status', 30)->default('submitted')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_reports');
    }
};