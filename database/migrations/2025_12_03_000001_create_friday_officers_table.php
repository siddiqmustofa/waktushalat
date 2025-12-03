<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('friday_officers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mosque_id');
            $table->date('date');
            $table->string('khatib')->nullable();
            $table->string('imam')->nullable();
            $table->string('muadzin')->nullable();
            $table->string('bilal')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('mosque_id')->references('id')->on('mosques')->onDelete('cascade');
            $table->unique(['mosque_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('friday_officers');
    }
};

