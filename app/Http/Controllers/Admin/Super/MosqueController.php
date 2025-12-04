<?php

namespace App\Http\Controllers\Admin\Super;

use App\Http\Controllers\Controller;
use App\Models\DisplaySetting;
use App\Models\Mosque;
use App\Models\PrayerSetting;
use App\Models\Announcement;
use App\Models\RunningText;
use App\Models\FridayOfficer;
use App\Models\Kajian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class MosqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mosques = Mosque::orderBy('name')->paginate(20);

        return view('admin.super.mosques.index', compact('mosques'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.super.mosques.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:mosques,slug',
            'address' => 'nullable|string',
            'timezone' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $mosque = Mosque::create($data);

        $template = Mosque::where('slug', 'mesjid10')->first();
        if ($template) {
            $tplDisplay = DisplaySetting::where('mosque_id', $template->id)->first();
            if ($tplDisplay) {
                DisplaySetting::updateOrCreate(
                    ['mosque_id' => $mosque->id],
                    [
                        'theme' => $tplDisplay->theme,
                        'primary_color' => $tplDisplay->primary_color,
                        'refresh_seconds' => $tplDisplay->refresh_seconds,
                        'show_running_text' => (bool) $tplDisplay->show_running_text,
                        'show_announcements' => (bool) $tplDisplay->show_announcements,
                        'info_seconds' => $tplDisplay->info_seconds,
                        'wallpaper_seconds' => $tplDisplay->wallpaper_seconds,
                        'wait_adzan_minutes' => $tplDisplay->wait_adzan_minutes,
                        'adzan_minutes' => $tplDisplay->adzan_minutes,
                        'sholat_minutes' => $tplDisplay->sholat_minutes,
                        'iqomah_minutes' => (array) $tplDisplay->iqomah_minutes,
                        'jumat_active' => (bool) $tplDisplay->jumat_active,
                        'tarawih_active' => (bool) $tplDisplay->tarawih_active,
                        'show_finance_card' => (bool) $tplDisplay->show_finance_card,
                        'show_friday_officer_card' => (bool) $tplDisplay->show_friday_officer_card,
                        'show_kajian_card' => (bool) $tplDisplay->show_kajian_card,
                        'infaq_jumat' => $tplDisplay->infaq_jumat,
                        'infaq_langsung' => $tplDisplay->infaq_langsung,
                        'saldo_kas' => $tplDisplay->saldo_kas,
                        'saldo_bank_syariah' => $tplDisplay->saldo_bank_syariah,
                        'sound_fajr' => (bool) $tplDisplay->sound_fajr,
                        'sound_dhuhr' => (bool) $tplDisplay->sound_dhuhr,
                        'sound_asr' => (bool) $tplDisplay->sound_asr,
                        'sound_maghrib' => (bool) $tplDisplay->sound_maghrib,
                        'sound_isha' => (bool) $tplDisplay->sound_isha,
                        'logo_path' => $tplDisplay->logo_path,
                        'wallpaper_paths' => (array) $tplDisplay->wallpaper_paths,
                    ]
                );
            }

            $tplPrayer = PrayerSetting::where('mosque_id', $template->id)->orderByDesc('id')->first();
            if ($tplPrayer) {
                PrayerSetting::create([
                    'mosque_id' => $mosque->id,
                    'fajr_time' => $tplPrayer->fajr_time,
                    'dhuhr_time' => $tplPrayer->dhuhr_time,
                    'asr_time' => $tplPrayer->asr_time,
                    'maghrib_time' => $tplPrayer->maghrib_time,
                    'isha_time' => $tplPrayer->isha_time,
                    'effective_date' => $tplPrayer->effective_date,
                    'use_auto_calculation' => (bool) $tplPrayer->use_auto_calculation,
                    'calculation_method' => $tplPrayer->calculation_method,
                    'calculation_adjust' => (array) $tplPrayer->calculation_adjust,
                    'calculation_tune' => (array) $tplPrayer->calculation_tune,
                    'latitude' => $tplPrayer->latitude,
                    'longitude' => $tplPrayer->longitude,
                    'timezone' => $mosque->timezone ?: $tplPrayer->timezone,
                    'myquran_city_code' => $tplPrayer->myquran_city_code,
                    'myquran_v3_city_id' => $tplPrayer->myquran_v3_city_id,
                ]);
            } else {
                PrayerSetting::create([
                    'mosque_id' => $mosque->id,
                    'fajr_time' => null,
                    'dhuhr_time' => null,
                    'asr_time' => null,
                    'maghrib_time' => null,
                    'isha_time' => null,
                    'effective_date' => null,
                    'use_auto_calculation' => false,
                    'timezone' => $mosque->timezone,
                ]);
            }

            $anns = Announcement::where('mosque_id', $template->id)->get();
            foreach ($anns as $a) {
                Announcement::create([
                    'mosque_id' => $mosque->id,
                    'title' => $a->title,
                    'body' => $a->body,
                    'is_active' => (bool) $a->is_active,
                    'starts_at' => $a->starts_at,
                    'ends_at' => $a->ends_at,
                ]);
            }

            $rts = RunningText::where('mosque_id', $template->id)->get();
            foreach ($rts as $rt) {
                RunningText::create([
                    'mosque_id' => $mosque->id,
                    'content' => $rt->content,
                    'is_active' => (bool) $rt->is_active,
                ]);
            }

            $fos = FridayOfficer::where('mosque_id', $template->id)->get();
            foreach ($fos as $fo) {
                FridayOfficer::create([
                    'mosque_id' => $mosque->id,
                    'date' => $fo->date,
                    'khatib' => $fo->khatib,
                    'imam' => $fo->imam,
                    'muadzin' => $fo->muadzin,
                    'bilal' => $fo->bilal,
                    'notes' => $fo->notes,
                ]);
            }

            $kjs = Kajian::where('mosque_id', $template->id)->get();
            foreach ($kjs as $kj) {
                Kajian::create([
                    'mosque_id' => $mosque->id,
                    'title' => $kj->title,
                    'speaker' => $kj->speaker,
                    'starts_at' => $kj->starts_at,
                    'ends_at' => $kj->ends_at,
                    'location' => $kj->location,
                    'notes' => $kj->notes,
                    'is_active' => (bool) $kj->is_active,
                ]);
            }
        } else {
            DisplaySetting::firstOrCreate(
                ['mosque_id' => $mosque->id],
                [
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
                ]
            );

            PrayerSetting::create([
                'mosque_id' => $mosque->id,
                'fajr_time' => null,
                'dhuhr_time' => null,
                'asr_time' => null,
                'maghrib_time' => null,
                'isha_time' => null,
                'effective_date' => null,
                'use_auto_calculation' => false,
                'timezone' => $mosque->timezone,
            ]);
        }

        if ($request->route()->named('public.mosques.store')) {
            $request->validate([
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $mosque->name,
                'email' => (string) $request->input('email'),
                'password' => Hash::make((string) $request->input('password')),
                'role' => 'mosque_admin',
                'mosque_id' => $mosque->id,
            ]);

            \Illuminate\Support\Facades\Auth::login($user);

            return redirect()->route('dashboard')->with('status', 'Registrasi berhasil. Akun admin terbuat dan otomatis login.');
        }

        $email = trim((string) ($request->input('email') ?? ''));
        $password = (string) ($request->input('password') ?? '');
        if ($email !== '' && $password !== '') {
            $request->validate([
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            User::create([
                'name' => $mosque->name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'mosque_admin',
                'mosque_id' => $mosque->id,
            ]);
        }

        return redirect()->route('mosques.index')->with('status', 'Masjid ditambahkan.');
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
    public function edit(Mosque $mosque)
    {
        return view('admin.super.mosques.edit', compact('mosque'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mosque $mosque)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:mosques,slug,'.$mosque->id,
            'address' => 'nullable|string',
            'timezone' => 'nullable|string',
            'is_active' => 'boolean',
            'admin_email' => 'nullable|string|lowercase|email|max:255',
            'admin_password' => 'nullable|confirmed|min:6',
        ]);
        $mosque->update($data);

        $admin = \App\Models\User::where('mosque_id', $mosque->id)->where('role','mosque_admin')->first();
        if ($admin) {
            $updates = [];
            if (($data['admin_email'] ?? '') !== '' && $data['admin_email'] !== $admin->email) {
                $request->validate([
                    'admin_email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email,'.$admin->id],
                ]);
                $updates['email'] = (string) $data['admin_email'];
            }
            if (($data['admin_password'] ?? '') !== '') {
                $updates['password'] = \Illuminate\Support\Facades\Hash::make((string) $data['admin_password']);
            }
            if (!empty($updates)) {
                $admin->update($updates);
            }
        }

        return redirect()->route('mosques.index')->with('status', 'Masjid diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mosque $mosque)
    {
        $mosque->delete();

        return redirect()->route('mosques.index');
    }
}
