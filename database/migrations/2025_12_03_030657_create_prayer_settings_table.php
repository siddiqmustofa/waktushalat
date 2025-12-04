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
        Schema::create('prayer_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mosque_id')->constrained('mosques')->cascadeOnDelete();
            $table->time('fajr_time')->nullable();
            $table->time('dhuhr_time')->nullable();
            $table->time('asr_time')->nullable();
            $table->time('maghrib_time')->nullable();
            $table->time('isha_time')->nullable();
            $table->date('effective_date')->nullable();
            $table->timestamps();
            $table->index(['mosque_id', 'id']);
            $table->index(['mosque_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayer_settings');
    }
};
