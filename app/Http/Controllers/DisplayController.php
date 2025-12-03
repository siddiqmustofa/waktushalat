<?php

namespace App\Http\Controllers;

use App\Models\Mosque;
use App\Models\FridayOfficer;
use App\Models\Kajian;
use Carbon\Carbon;

class DisplayController extends Controller
{
    public function show(string $slug)
    {
        $mosque = Mosque::with([
            'displaySetting',
            'announcements' => function ($q) {
                $q->where('is_active', true);
            },
            'runningTexts' => function ($q) {
                $q->where('is_active', true);
            },
            'prayerSettings' => function ($q) {
                $q->latest();
            },
        ])->where('slug', $slug)->where('is_active', true)->firstOrFail();

        $now = Carbon::now($mosque->timezone ?? 'UTC');
        $prayer = optional($mosque->prayerSettings->first());

        $nextFridayOfficer = FridayOfficer::where('mosque_id', $mosque->id)
            ->where('date', '>=', $now->toDateString())
            ->orderBy('date')
            ->first();

        $upcomingKajians = Kajian::where('mosque_id', $mosque->id)
            ->where('is_active', true)
            ->where('starts_at', '>=', $now)
            ->orderBy('starts_at')
            ->limit(5)
            ->get();

        return view('display.show', [
            'mosque' => $mosque,
            'now' => $now,
            'prayer' => $prayer,
            'display' => $mosque->displaySetting,
            'nextFridayOfficer' => $nextFridayOfficer,
            'upcomingKajians' => $upcomingKajians,
        ]);
    }

    public function pulse(string $slug)
    {
        $mosque = Mosque::with([
            'displaySetting',
            'announcements' => function ($q) {
                $q->where('is_active', true);
            },
            'runningTexts' => function ($q) {
                $q->where('is_active', true);
            },
            'prayerSettings' => function ($q) {
                $q->latest();
            },
        ])->where('slug', $slug)->where('is_active', true)->firstOrFail();

        $now = Carbon::now($mosque->timezone ?? 'UTC');

        $hash = sha1(json_encode([
            'display' => $mosque->displaySetting,
            'announcements' => $mosque->announcements,
            'runningTexts' => $mosque->runningTexts,
            'prayer' => optional($mosque->prayerSettings->first()),
            'fridayOfficer' => \App\Models\FridayOfficer::where('mosque_id', $mosque->id)->where('date', '>=', $now->toDateString())->orderBy('date')->first(),
            'kajians' => \App\Models\Kajian::where('mosque_id', $mosque->id)->where('is_active', true)->where('starts_at', '>=', $now)->orderBy('starts_at')->limit(5)->get(),
        ]));

        return response()->json([
            'hash' => $hash,
            'time' => $now->toIso8601String(),
        ]);
    }
}
