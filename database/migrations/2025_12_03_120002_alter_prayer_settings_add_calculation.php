<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prayer_settings', function (Blueprint $table) {
            $table->boolean('use_auto_calculation')->default(false)->after('effective_date');
            $table->string('calculation_method')->nullable()->after('use_auto_calculation');
            $table->json('calculation_adjust')->nullable()->after('calculation_method');
            $table->json('calculation_tune')->nullable()->after('calculation_adjust');
            $table->decimal('latitude', 9, 6)->nullable()->after('calculation_tune');
            $table->decimal('longitude', 9, 6)->nullable()->after('latitude');
            $table->string('timezone')->nullable()->after('longitude');
            $table->boolean('dst')->default(false)->after('timezone');
        });
    }

    public function down(): void
    {
        Schema::table('prayer_settings', function (Blueprint $table) {
            $table->dropColumn([
                'use_auto_calculation',
                'calculation_method',
                'calculation_adjust',
                'calculation_tune',
                'latitude',
                'longitude',
                'timezone',
                'dst',
            ]);
        });
    }
};
