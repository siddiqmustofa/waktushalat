<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kajians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mosque_id');
            $table->string('title');
            $table->string('speaker')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign key dihapus untuk mencegah error urutan migrasi pada environment terbatas
            $table->index(['mosque_id', 'starts_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kajians');
    }
};
