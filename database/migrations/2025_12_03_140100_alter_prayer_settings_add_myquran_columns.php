<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prayer_settings', function (Blueprint $table) {
            $table->string('myquran_city_code')->nullable()->after('timezone');
            $table->string('myquran_v3_city_id')->nullable()->after('myquran_city_code');
        });
    }

    public function down(): void
    {
        Schema::table('prayer_settings', function (Blueprint $table) {
            $table->dropColumn(['myquran_city_code', 'myquran_v3_city_id']);
        });
    }
};

