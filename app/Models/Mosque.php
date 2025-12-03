<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mosque extends Model
{
    protected $fillable = [
        'name', 'slug', 'address', 'timezone', 'is_active',
    ];

    public function prayerSettings()
    {
        return $this->hasMany(PrayerSetting::class);
    }

    public function displaySetting()
    {
        return $this->hasOne(DisplaySetting::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function runningTexts()
    {
        return $this->hasMany(RunningText::class);
    }

    public function fridayOfficers()
    {
        return $this->hasMany(FridayOfficer::class);
    }

    public function kajians()
    {
        return $this->hasMany(Kajian::class);
    }
}
