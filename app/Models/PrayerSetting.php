<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrayerSetting extends Model
{
    protected $fillable = [
        'mosque_id',
        'fajr_time',
        'dhuhr_time',
        'asr_time',
        'maghrib_time',
        'isha_time',
        'effective_date',
        'use_auto_calculation',
        'calculation_method',
        'calculation_adjust',
        'calculation_tune',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'fajr_time' => 'datetime:H:i',
        'dhuhr_time' => 'datetime:H:i',
        'asr_time' => 'datetime:H:i',
        'maghrib_time' => 'datetime:H:i',
        'isha_time' => 'datetime:H:i',
        'use_auto_calculation' => 'boolean',
        'calculation_adjust' => 'array',
        'calculation_tune' => 'array',
    ];

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }
}
