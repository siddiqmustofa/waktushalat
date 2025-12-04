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
        'latitude',
        'longitude',
        'timezone',
        'myquran_city_code',
        'myquran_v3_city_id',
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
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
        'myquran_city_code' => 'string',
        'myquran_v3_city_id' => 'string',
    ];

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }
}
