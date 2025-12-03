<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('display_settings', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('show_announcements');
            $table->json('wallpaper_paths')->nullable()->after('logo_path');

            $table->unsignedInteger('info_seconds')->default(5)->after('wallpaper_paths');
            $table->unsignedInteger('wallpaper_seconds')->default(15)->after('info_seconds');
            $table->unsignedInteger('wait_adzan_minutes')->default(1)->after('wallpaper_seconds');
            $table->unsignedInteger('adzan_minutes')->default(3)->after('wait_adzan_minutes');
            $table->unsignedInteger('sholat_minutes')->default(20)->after('adzan_minutes');
            $table->json('iqomah_minutes')->nullable()->after('sholat_minutes');

            $table->boolean('jumat_active')->default(false)->after('iqomah_minutes');
            $table->unsignedInteger('jumat_duration_minutes')->nullable()->after('jumat_active');
            $table->string('jumat_text')->nullable()->after('jumat_duration_minutes');

            $table->boolean('tarawih_active')->default(false)->after('jumat_text');
            $table->unsignedInteger('tarawih_duration_minutes')->nullable()->after('tarawih_active');

            $table->boolean('sound_fajr')->default(true)->after('tarawih_duration_minutes');
            $table->boolean('sound_dhuhr')->default(true)->after('sound_fajr');
            $table->boolean('sound_asr')->default(true)->after('sound_dhuhr');
            $table->boolean('sound_maghrib')->default(true)->after('sound_asr');
            $table->boolean('sound_isha')->default(true)->after('sound_maghrib');
        });
    }

    public function down(): void
    {
        Schema::table('display_settings', function (Blueprint $table) {
            $table->dropColumn([
                'logo_path',
                'wallpaper_paths',
                'info_seconds',
                'wallpaper_seconds',
                'wait_adzan_minutes',
                'adzan_minutes',
                'sholat_minutes',
                'iqomah_minutes',
                'jumat_active',
                'jumat_duration_minutes',
                'jumat_text',
                'tarawih_active',
                'tarawih_duration_minutes',
                'sound_fajr',
                'sound_dhuhr',
                'sound_asr',
                'sound_maghrib',
                'sound_isha',
            ]);
        });
    }
};
