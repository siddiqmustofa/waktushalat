<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('display_settings', function (Blueprint $table) {
            $table->boolean('show_finance_card')->default(true)->after('tarawih_duration_minutes');
            $table->boolean('show_friday_officer_card')->default(true)->after('show_finance_card');
            $table->boolean('show_kajian_card')->default(true)->after('show_friday_officer_card');
        });
    }

    public function down(): void
    {
        Schema::table('display_settings', function (Blueprint $table) {
            $table->dropColumn([
                'show_finance_card',
                'show_friday_officer_card',
                'show_kajian_card',
            ]);
        });
    }
};
