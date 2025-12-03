<?php

namespace App\Http\Controllers\Admin\Mosque;

use App\Http\Controllers\Controller;
use App\Models\DisplaySetting;
use App\Models\PrayerSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Support\PrayTimes as PrayTimesSupport;

class MosqueSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mosque = Auth::user()->mosque_id;
        $prayer = PrayerSetting::where('mosque_id', $mosque)->latest()->first();
        $display = DisplaySetting::where('mosque_id', $mosque)->first();

        return view('admin.mosque.settings.index', compact('prayer', 'display'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $mosque = Auth::user()->mosque_id;
        $display = DisplaySetting::where('mosque_id', $mosque)->first();
        $data = $request->validate([
            'fajr_time' => 'nullable',
            'dhuhr_time' => 'nullable',
            'asr_time' => 'nullable',
            'maghrib_time' => 'nullable',
            'isha_time' => 'nullable',
            'theme' => 'nullable|string',
            'primary_color' => 'nullable|string',
            'refresh_seconds' => 'nullable|integer',
            'show_running_text' => 'sometimes|boolean',
            'show_announcements' => 'sometimes|boolean',
            'show_finance_card' => 'sometimes|boolean',
            'show_friday_officer_card' => 'sometimes|boolean',
            'show_kajian_card' => 'sometimes|boolean',
            'logo' => 'nullable|image|max:2048',
            'wallpapers' => 'nullable',
            'wallpapers.*' => 'image|max:4096',
            'info_seconds' => 'nullable|integer',
            'wallpaper_seconds' => 'nullable|integer',
            'wait_adzan_minutes' => 'nullable|integer',
            'adzan_minutes' => 'nullable|integer',
            'sholat_minutes' => 'nullable|integer',
            'iqomah_minutes_fajr' => 'nullable|integer',
            'iqomah_minutes_dhuhr' => 'nullable|integer',
            'iqomah_minutes_asr' => 'nullable|integer',
            'iqomah_minutes_maghrib' => 'nullable|integer',
            'iqomah_minutes_isha' => 'nullable|integer',
            'jumat_active' => 'nullable|boolean',
            'jumat_duration_minutes' => 'nullable|integer',
            'jumat_text' => 'nullable|string',
            'tarawih_active' => 'nullable|boolean',
            'tarawih_duration_minutes' => 'nullable|integer',
            'infaq_jumat' => 'nullable|numeric',
            'infaq_langsung' => 'nullable|numeric',
            'saldo_kas' => 'nullable|numeric',
            'saldo_bank_syariah' => 'nullable|numeric',
            'calculation_method' => 'nullable|string',
            'timezone' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'calculation_adjust' => 'nullable',
            'calculation_tune' => 'nullable',
        ]);

        $auto = ($data['calculation_method'] ?? '0') !== '0'
            && ($data['latitude'] ?? null) !== null
            && ($data['longitude'] ?? null) !== null
            && ($data['timezone'] ?? null) !== null;

        if ($auto) {
            $tz = (string) $data['timezone'];
            $now = Carbon::now($tz);
            $offsetHours = $now->offset / 3600;
            $pt = new PrayTimesSupport((string) $data['calculation_method']);
            $adjust = $data['calculation_adjust'] ?? null;
            if (is_string($adjust)) {
                $decoded = json_decode($adjust, true);
                if (is_array($decoded)) $adjust = $decoded;
            }
            if (is_array($adjust)) {
                $pt->adjust($adjust);
            }
            $tune = $data['calculation_tune'] ?? null;
            if (is_string($tune)) {
                $decoded = json_decode($tune, true);
                if (is_array($decoded)) $tune = $decoded;
            }
            if (is_array($tune)) {
                $pt->tune($tune);
            }
            $times = $pt->getTimes($now, [ (float) $data['latitude'], (float) $data['longitude'] ], (float) $offsetHours, false);
            $data['fajr_time'] = $times['fajr'] ?? null;
            $data['dhuhr_time'] = $times['dhuhr'] ?? null;
            $data['asr_time'] = $times['asr'] ?? null;
            $data['maghrib_time'] = $times['maghrib'] ?? null;
            $data['isha_time'] = $times['isha'] ?? null;
        }

        PrayerSetting::create(array_merge(
            ['mosque_id' => $mosque, 'use_auto_calculation' => $auto],
            collect($data)->only(['fajr_time', 'dhuhr_time', 'asr_time', 'maghrib_time', 'isha_time', 'calculation_method', 'calculation_adjust', 'calculation_tune', 'latitude', 'longitude', 'timezone'])->all()
        ));

        $payload = collect($data)->only([
            'theme',
            'primary_color',
            'refresh_seconds',
            'show_running_text',
            'show_announcements',
            'info_seconds',
            'wallpaper_seconds',
            'wait_adzan_minutes',
            'adzan_minutes',
            'sholat_minutes',
            'jumat_active',
            'jumat_duration_minutes',
            'jumat_text',
            'tarawih_active',
            'tarawih_duration_minutes',
            'infaq_jumat',
            'infaq_langsung',
            'saldo_kas',
            'saldo_bank_syariah',
        ])->all();

        $payload['show_finance_card'] = $request->boolean('show_finance_card');
        $payload['show_friday_officer_card'] = $request->boolean('show_friday_officer_card');
        $payload['show_kajian_card'] = $request->boolean('show_kajian_card');
        $payload['sound_fajr'] = $request->boolean('sound_fajr');
        $payload['sound_dhuhr'] = $request->boolean('sound_dhuhr');
        $payload['sound_asr'] = $request->boolean('sound_asr');
        $payload['sound_maghrib'] = $request->boolean('sound_maghrib');
        $payload['sound_isha'] = $request->boolean('sound_isha');

        if ($request->file('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $payload['logo_path'] = $path;
        }

        if ($request->file('wallpapers')) {
            $paths = (array) (optional($display)->wallpaper_paths ?? []);
            foreach ($request->file('wallpapers') as $file) {
                $paths[] = $file->store('wallpapers', 'public');
            }
            $payload['wallpaper_paths'] = $paths;
        }

        $iqomah = [
            'fajr' => (int) ($data['iqomah_minutes_fajr'] ?? 0),
            'dhuhr' => (int) ($data['iqomah_minutes_dhuhr'] ?? 0),
            'asr' => (int) ($data['iqomah_minutes_asr'] ?? 0),
            'maghrib' => (int) ($data['iqomah_minutes_maghrib'] ?? 0),
            'isha' => (int) ($data['iqomah_minutes_isha'] ?? 0),
        ];
        $payload['iqomah_minutes'] = $iqomah;

        DisplaySetting::updateOrCreate(
            ['mosque_id' => $mosque],
            $payload
        );

        return back()->with('status', 'Pengaturan tersimpan.');
    }

    public function deleteLogo(Request $request)
    {
        $mosque = Auth::user()->mosque_id;
        $display = DisplaySetting::where('mosque_id', $mosque)->firstOrFail();
        if ($display->logo_path) {
            Storage::disk('public')->delete($display->logo_path);
            $display->update(['logo_path' => null]);
        }
        return back()->with('status', 'Logo dihapus.');
    }

    public function deleteWallpaper(Request $request)
    {
        $mosque = Auth::user()->mosque_id;
        $display = DisplaySetting::where('mosque_id', $mosque)->firstOrFail();
        $path = $request->validate(['path' => 'required|string'])['path'];
        $paths = (array) ($display->wallpaper_paths ?? []);
        $paths = array_values(array_filter($paths, fn($p) => $p !== $path));
        Storage::disk('public')->delete($path);
        $display->update(['wallpaper_paths' => $paths]);
        return back()->with('status', 'Wallpaper dihapus.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
