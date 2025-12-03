<?php

use App\Models\DisplaySetting;
use App\Models\Mosque;
use App\Models\PrayerSetting;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('simulate:shalat {slug} {waktu?} {--fast}', function () {
    $slug = (string) $this->argument('slug');
    $waktu = (string) ($this->argument('waktu') ?? 'dhuhr');
    $fast = (bool) $this->option('fast');

    $mosque = Mosque::where('slug', $slug)->first();
    if (! $mosque) {
        $this->error('Mosque not found for slug: '.$slug);

        return 1;
    }

    $now = Carbon::now($mosque->timezone ?? 'UTC');

    $ps = PrayerSetting::where('mosque_id', $mosque->id)->latest()->first();
    if (! $ps) {
        $ps = new PrayerSetting(['mosque_id' => $mosque->id]);
    }

    $times = [
        'fajr_time' => $ps->fajr_time,
        'dhuhr_time' => $ps->dhuhr_time,
        'asr_time' => $ps->asr_time,
        'maghrib_time' => $ps->maghrib_time,
        'isha_time' => $ps->isha_time,
    ];

    $map = [
        'fajr' => 'fajr_time',
        'dhuhr' => 'dhuhr_time',
        'asr' => 'asr_time',
        'maghrib' => 'maghrib_time',
        'isha' => 'isha_time',
    ];

    $key = $map[$waktu] ?? 'dhuhr_time';
    foreach ($times as $k => $v) {
        $times[$k] = null;
    }
    $times[$key] = $now->copy();

    $ps->fill(array_merge($times, ['effective_date' => $now->toDateString()]));
    $ps->save();

    $ds = DisplaySetting::firstOrCreate(
        ['mosque_id' => $mosque->id],
        ['theme' => 'dark', 'show_running_text' => true, 'show_announcements' => true]
    );

    if ($fast) {
        $ds->wait_adzan_minutes = 0;
        $ds->adzan_minutes = 1;
        $ds->sholat_minutes = 2;
        $ds->iqomah_minutes = array_merge((array) ($ds->iqomah_minutes ?? []), [
            'fajr' => 1, 'dhuhr' => 1, 'asr' => 1, 'maghrib' => 1, 'isha' => 1,
        ]);
        $ds->save();
    }

    $this->info('Simulasi shalat: '.$waktu.' untuk slug '.$slug.' pada '.$now->toDateTimeString());
    $this->info('Buka halaman display dan reload jika belum auto-refresh.');

    return 0;
})->purpose('Simulasi adzan/iqomah untuk waktu shalat');

Artisan::command('simulate:jumat {slug} {--fast}', function () {
    $slug = (string) $this->argument('slug');
    $fast = (bool) $this->option('fast');

    $mosque = Mosque::where('slug', $slug)->first();
    if (! $mosque) {
        $this->error('Mosque not found for slug: '.$slug);

        return 1;
    }

    $now = Carbon::now($mosque->timezone ?? 'UTC');

    $ps = PrayerSetting::where('mosque_id', $mosque->id)->latest()->first();
    if (! $ps) {
        $ps = new PrayerSetting(['mosque_id' => $mosque->id]);
    }
    $ps->fill([
        'dhuhr_time' => $now->copy(),
        'effective_date' => $now->toDateString(),
    ]);
    $ps->save();

    $ds = DisplaySetting::firstOrCreate(
        ['mosque_id' => $mosque->id],
        ['theme' => 'dark', 'show_running_text' => true, 'show_announcements' => true]
    );
    $ds->jumat_active = true;
    if ($fast) {
        $ds->jumat_duration_minutes = 5;
        $ds->wait_adzan_minutes = 0;
        $ds->adzan_minutes = 1;
        $ds->sholat_minutes = 2;
    }
    $ds->jumat_text = $ds->jumat_text ?: 'Harap diam saat khutbah';
    $ds->save();

    $this->info('Simulasi jumatan untuk slug '.$slug.' pada '.$now->toDateTimeString().' (Dzuhur)');
    $this->info('Buka halaman display dan reload jika belum auto-refresh.');

    return 0;
})->purpose('Simulasi adzan sholat jum’at (menggunakan waktu Dzuhur)');

Artisan::command('make:mosque {name} {slug} {timezone?}', function () {
    $name = (string) $this->argument('name');
    $slug = (string) $this->argument('slug');
    $tz = (string) ($this->argument('timezone') ?? 'Asia/Jakarta');

    $mosque = \App\Models\Mosque::firstOrCreate([
        'slug' => $slug,
    ], [
        'name' => $name,
        'address' => '',
        'timezone' => $tz,
        'is_active' => true,
    ]);

    \App\Models\DisplaySetting::firstOrCreate([
        'mosque_id' => $mosque->id,
    ], [
        'theme' => 'dark',
        'primary_color' => '#6366f1',
        'refresh_seconds' => 15,
        'show_running_text' => true,
        'show_announcements' => true,
        'info_seconds' => 5,
        'wallpaper_seconds' => 15,
        'wait_adzan_minutes' => 1,
        'adzan_minutes' => 3,
        'sholat_minutes' => 20,
        'iqomah_minutes' => [
            'fajr' => 10,
            'dhuhr' => 10,
            'asr' => 10,
            'maghrib' => 10,
            'isha' => 10,
        ],
        'jumat_active' => false,
        'tarawih_active' => false,
        'show_finance_card' => true,
        'show_friday_officer_card' => true,
        'show_kajian_card' => true,
        'infaq_jumat' => 0,
        'infaq_langsung' => 0,
        'saldo_kas' => 0,
        'saldo_bank_syariah' => 0,
        'sound_fajr' => true,
        'sound_dhuhr' => true,
        'sound_asr' => true,
        'sound_maghrib' => true,
        'sound_isha' => true,
    ]);

    \App\Models\PrayerSetting::firstOrCreate([
        'mosque_id' => $mosque->id,
        'effective_date' => Carbon::now($tz)->toDateString(),
    ], [
        'fajr_time' => null,
        'dhuhr_time' => null,
        'asr_time' => null,
        'maghrib_time' => null,
        'isha_time' => null,
    ]);

    $this->info('Mosque created: '.$name.' ('.$slug.')');

    return 0;
})->purpose('Buat masjid contoh dengan pengaturan default');
