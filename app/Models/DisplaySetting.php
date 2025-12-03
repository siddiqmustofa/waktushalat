<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisplaySetting extends Model
{
    protected $fillable = [
        'mosque_id',
        'theme',
        'primary_color',
        'refresh_seconds',
        'show_running_text',
        'show_announcements',
        'show_finance_card',
        'show_friday_officer_card',
        'show_kajian_card',
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
        'infaq_jumat',
        'infaq_langsung',
        'saldo_kas',
        'saldo_bank_syariah',
        'sound_fajr',
        'sound_dhuhr',
        'sound_asr',
        'sound_maghrib',
        'sound_isha',
    ];

    protected $casts = [
        'show_running_text' => 'boolean',
        'show_announcements' => 'boolean',
        'show_finance_card' => 'boolean',
        'show_friday_officer_card' => 'boolean',
        'show_kajian_card' => 'boolean',
        'wallpaper_paths' => 'array',
        'iqomah_minutes' => 'array',
        'jumat_active' => 'boolean',
        'tarawih_active' => 'boolean',
        'sound_fajr' => 'boolean',
        'sound_dhuhr' => 'boolean',
        'sound_asr' => 'boolean',
        'sound_maghrib' => 'boolean',
        'sound_isha' => 'boolean',
    ];

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }
}
