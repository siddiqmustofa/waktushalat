<?php

namespace Database\Seeders;

use App\Models\DisplaySetting;
use App\Models\Mosque;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $mosque = Mosque::create([
            'name' => 'Masjid Contoh',
            'slug' => 'masjid-contoh',
            'address' => 'Jl. Contoh No. 1',
            'timezone' => 'Asia/Jakarta',
            'is_active' => true,
        ]);

        DisplaySetting::create([
            'mosque_id' => $mosque->id,
            'theme' => 'dark',
            'primary_color' => '#22c55e',
            'refresh_seconds' => 30,
            'show_running_text' => true,
            'show_announcements' => true,
            'info_seconds' => 5,
            'wallpaper_seconds' => 15,
            'wait_adzan_minutes' => 1,
            'adzan_minutes' => 3,
            'sholat_minutes' => 20,
            'iqomah_minutes' => json_encode([
                'fajr' => 10,
                'dhuhr' => 10,
                'asr' => 10,
                'maghrib' => 10,
                'isha' => 10,
            ]),
            'sound_fajr' => true,
            'sound_dhuhr' => true,
            'sound_asr' => true,
            'sound_maghrib' => true,
            'sound_isha' => true,
        ]);

        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'super@example.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);

        User::factory()->create([
            'name' => 'Admin Masjid',
            'email' => 'admin@masjidcontoh.com',
            'password' => bcrypt('password'),
            'role' => 'mosque_admin',
            'mosque_id' => $mosque->id,
        ]);
    }
}
