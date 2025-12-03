<?php

namespace App\Http\Controllers\Admin\Super;

use App\Http\Controllers\Controller;
use App\Models\DisplaySetting;
use App\Models\Mosque;
use App\Models\PrayerSetting;
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
        ]);
        $mosque->update($data);

        return redirect()->route('mosques.index');
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
