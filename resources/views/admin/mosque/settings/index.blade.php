<x-app-layout>
    <x-slot name="header">
        Pengaturan Display & Jadwal
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto space-y-8">
        @if(session('status'))
            <div class="mx-6">
                <div class="rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 px-4 py-3">{{ session('status') }}</div>
            </div>
        @endif
        <form action="{{ route('settings.store') }}" method="post" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="bg-white dark:bg-gray-800 border rounded-2xl shadow-sm">
                <div class="px-6 py-5 border-b flex items-center justify-between">
                    <div>
                        <div class="text-xl font-semibold">Media</div>
                        <div class="text-sm text-slate-500">Logo dan wallpaper untuk slideshow latar.</div>
                    </div>
                    <div class="text-xs px-2 py-1 rounded bg-indigo-50 text-indigo-600">UI Modern</div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-medium">Logo</label>
                        <input type="file" name="logo" accept="image/*" class="mt-2 w-full file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @if(optional($display)->logo_path)
                            <div class="mt-3">
                                <img src="{{ asset('storage/' . $display->logo_path) }}" class="h-16 rounded-lg border" alt="logo">
                                <button type="submit" form="delete-logo-form" class="mt-2 px-3 py-1.5 bg-red-600 text-white rounded">Hapus logo</button>
                            </div>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Wallpapers</label>
                        <input type="file" name="wallpapers[]" multiple accept="image/*" class="mt-2 w-full file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @if(optional($display)->wallpaper_paths)
                            <div class="mt-3 grid grid-cols-2 md:grid-cols-5 gap-3">
                                @foreach($display->wallpaper_paths as $wp)
                                    <div>
                                        <img src="{{ asset('storage/' . $wp) }}" class="h-20 w-full object-cover rounded-lg border" alt="wp">
                                        <button type="submit" form="delete-wallpaper-{{ md5($wp) }}" class="mt-1 px-3 py-1.5 bg-red-600 text-white rounded w-full">Hapus</button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border rounded-2xl shadow-sm">
                <div class="px-6 py-4 border-b">
                    <div class="text-lg font-semibold">Timer</div>
                    <div class="text-sm text-slate-500">Durasi slideshow, jeda adzan, dan durasi adzan/sholat.</div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium">Durasi pengumuman (detik)</label>
                        <input type="number" name="info_seconds" value="{{ old('info_seconds', optional($display)->info_seconds) }}" class="mt-2 w-full" placeholder="5">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Detik wallpaper</label>
                        <input type="number" name="wallpaper_seconds" value="{{ old('wallpaper_seconds', optional($display)->wallpaper_seconds) }}" class="mt-2 w-full" placeholder="15">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Menit sebelum adzan</label>
                        <input type="number" name="wait_adzan_minutes" value="{{ old('wait_adzan_minutes', optional($display)->wait_adzan_minutes) }}" class="mt-2 w-full" placeholder="1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Menit durasi adzan</label>
                        <input type="number" name="adzan_minutes" value="{{ old('adzan_minutes', optional($display)->adzan_minutes) }}" class="mt-2 w-full" placeholder="3">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Menit durasi sholat</label>
                        <input type="number" name="sholat_minutes" value="{{ old('sholat_minutes', optional($display)->sholat_minutes) }}" class="mt-2 w-full" placeholder="20">
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border rounded-2xl shadow-sm">
                <div class="px-6 py-4 border-b">
                    <div class="text-lg font-semibold">Durasi Iqomah</div>
                    <div class="text-sm text-slate-500">Set durasi iqomah per waktu sholat.</div>
                </div>
                <div class="p-6 grid grid-cols-2 md:grid-cols-5 gap-6">
                    <div>
                        <label class="block text-sm font-medium">Iqomah Subuh</label>
                        <input type="number" name="iqomah_minutes_fajr" value="{{ old('iqomah_minutes_fajr', optional($display)->iqomah_minutes['fajr'] ?? 10) }}" class="mt-2 w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Iqomah Dzuhur</label>
                        <input type="number" name="iqomah_minutes_dhuhr" value="{{ old('iqomah_minutes_dhuhr', optional($display)->iqomah_minutes['dhuhr'] ?? 10) }}" class="mt-2 w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Iqomah Ashar</label>
                        <input type="number" name="iqomah_minutes_asr" value="{{ old('iqomah_minutes_asr', optional($display)->iqomah_minutes['asr'] ?? 10) }}" class="mt-2 w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Iqomah Maghrib</label>
                        <input type="number" name="iqomah_minutes_maghrib" value="{{ old('iqomah_minutes_maghrib', optional($display)->iqomah_minutes['maghrib'] ?? 10) }}" class="mt-2 w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Iqomah Isya</label>
                        <input type="number" name="iqomah_minutes_isha" value="{{ old('iqomah_minutes_isha', optional($display)->iqomah_minutes['isha'] ?? 10) }}" class="mt-2 w-full">
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border rounded-2xl shadow-sm">
                <div class="px-6 py-4 border-b">
                    <div class="text-lg font-semibold">Jumat & Tarawih</div>
                    <div class="text-sm text-slate-500">Opsi khusus untuk hari Jumat dan bulan Ramadhan.</div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center gap-3">
                        <input id="jumat_active" type="checkbox" name="jumat_active" value="1" {{ old('jumat_active', optional($display)->jumat_active) ? 'checked' : '' }}>
                        <label for="jumat_active" class="text-sm font-medium">Jumat aktif</label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Durasi khutbah (menit)</label>
                        <input type="number" name="jumat_duration_minutes" value="{{ old('jumat_duration_minutes', optional($display)->jumat_duration_minutes) }}" class="mt-2 w-full">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Teks khutbah</label>
                        <input type="text" name="jumat_text" value="{{ old('jumat_text', optional($display)->jumat_text) }}" class="mt-2 w-full" placeholder="Masukkan pesan kutbah singkat">
                    </div>
                    <div class="flex items-center gap-3">
                        <input id="tarawih_active" type="checkbox" name="tarawih_active" value="1" {{ old('tarawih_active', optional($display)->tarawih_active) ? 'checked' : '' }}>
                        <label for="tarawih_active" class="text-sm font-medium">Tarawih aktif</label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Durasi tarawih (menit)</label>
                        <input type="number" name="tarawih_duration_minutes" value="{{ old('tarawih_duration_minutes', optional($display)->tarawih_duration_minutes) }}" class="mt-2 w-full">
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border rounded-2xl shadow-sm">
                <div class="px-6 py-4 border-b">
                    <div class="text-lg font-semibold">Pengaturan Suara Adzan</div>
                    <div class="text-sm text-slate-500">Aktif/nonaktifkan suara untuk tiap waktu sholat.</div>
                </div>
                <div class="p-6 grid grid-cols-2 md:grid-cols-5 gap-6">
                    <div class="flex items-center gap-3">
                        <input id="sound_fajr" type="checkbox" name="sound_fajr" value="1" {{ old('sound_fajr', optional($display)->sound_fajr) ? 'checked' : '' }}>
                        <label for="sound_fajr" class="text-sm font-medium">Subuh</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <input id="sound_dhuhr" type="checkbox" name="sound_dhuhr" value="1" {{ old('sound_dhuhr', optional($display)->sound_dhuhr) ? 'checked' : '' }}>
                        <label for="sound_dhuhr" class="text-sm font-medium">Dzuhur</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <input id="sound_asr" type="checkbox" name="sound_asr" value="1" {{ old('sound_asr', optional($display)->sound_asr) ? 'checked' : '' }}>
                        <label for="sound_asr" class="text-sm font-medium">Ashar</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <input id="sound_maghrib" type="checkbox" name="sound_maghrib" value="1" {{ old('sound_maghrib', optional($display)->sound_maghrib) ? 'checked' : '' }}>
                        <label for="sound_maghrib" class="text-sm font-medium">Maghrib</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <input id="sound_isha" type="checkbox" name="sound_isha" value="1" {{ old('sound_isha', optional($display)->sound_isha) ? 'checked' : '' }}>
                        <label for="sound_isha" class="text-sm font-medium">Isya</label>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border rounded-2xl shadow-sm">
                <div class="px-6 py-4 border-b">
                    <div class="text-lg font-semibold">Tampilkan Kartu</div>
                    <div class="text-sm text-slate-500">Atur visibilitas kartu di halaman display.</div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center gap-3">
                        <input id="show_finance_card" type="checkbox" name="show_finance_card" value="1" {{ old('show_finance_card', optional($display)->show_finance_card) ? 'checked' : '' }}>
                        <label for="show_finance_card" class="text-sm font-medium">Saldo Kas Mesjid</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <input id="show_friday_officer_card" type="checkbox" name="show_friday_officer_card" value="1" {{ old('show_friday_officer_card', optional($display)->show_friday_officer_card) ? 'checked' : '' }}>
                        <label for="show_friday_officer_card" class="text-sm font-medium">Petugas Jumat</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <input id="show_kajian_card" type="checkbox" name="show_kajian_card" value="1" {{ old('show_kajian_card', optional($display)->show_kajian_card) ? 'checked' : '' }}>
                        <label for="show_kajian_card" class="text-sm font-medium">Jadwal Kajian</label>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border rounded-2xl shadow-sm">
                <div class="px-6 py-4 border-b">
                    <div class="text-lg font-semibold">Saldo Kas Mesjid</div>
                    <div class="text-sm text-slate-500">Masukkan saldo terkini untuk ditampilkan di halaman display.</div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium">Infaq Jumat</label>
                        <input type="number" step="0.01" name="infaq_jumat" value="{{ old('infaq_jumat', optional($display)->infaq_jumat) }}" class="mt-2 w-full" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Infaq</label>
                        <input type="number" step="0.01" name="infaq_langsung" value="{{ old('infaq_langsung', optional($display)->infaq_langsung) }}" class="mt-2 w-full" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Saldo Kas</label>
                        <input type="number" step="0.01" name="saldo_kas" value="{{ old('saldo_kas', optional($display)->saldo_kas) }}" class="mt-2 w-full" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Saldo Bank</label>
                        <input type="number" step="0.01" name="saldo_bank_syariah" value="{{ old('saldo_bank_syariah', optional($display)->saldo_bank_syariah) }}" class="mt-2 w-full" placeholder="0">
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border rounded-2xl shadow-sm">
                <div class="px-6 py-4 border-b">
                    <div class="text-lg font-semibold">Kalkulasi Otomatis</div>
                    <div class="text-sm text-slate-500">Pilih antara koordinat atau API MyQuran.</div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium">Metode kalkulasi</label>
                        <select name="calculation_method" class="mt-2 w-full">
                            @php($methods = ['0'=>'Manual','MyQuranV3'=>'MyQuran v3 (API)'])
                            @foreach($methods as $k=>$v)
                                <option value="{{ $k }}" @selected(old('calculation_method', optional($prayer)->calculation_method) == $k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">MyQuran v3 City ID</label>
                        <input type="text" name="myquran_v3_city_id" value="{{ old('myquran_v3_city_id', optional($prayer)->myquran_v3_city_id) }}" class="mt-2 w-full" placeholder="eda80a3d5b344bc40f3bc04f65b7a357">
                        <div class="text-xs mt-1 text-slate-500">Gunakan ID unik kota dari MyQuran v3.</div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Cari Kota (MyQuran v3)</label>
                        <div class="mt-2 flex items-center gap-2">
                            <input type="text" id="v3-city-search" class="w-full" placeholder="bandung">
                            <button type="button" id="btn-v3-search" class="px-3 py-1.5 bg-indigo-600 text-white rounded">Cari</button>
                        </div>
                        <select id="v3-city-results" class="mt-2 w-full">
                            <option value="">Pilih hasil…</option>
                        </select>
                        <div class="text-xs mt-1 text-slate-500">Pilih kab/kota untuk mengisi MyQuran v3 City ID.</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Timezone (IANA)</label>
                        <input type="text" name="timezone" value="{{ old('timezone', optional($prayer)->timezone) }}" class="mt-2 w-full" placeholder="Asia/Jakarta">
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border rounded-2xl shadow-sm">
                <div class="px-6 py-4 border-b">
                    <div class="text-lg font-semibold">Waktu Sholat Manual</div>
                    <div class="text-sm text-slate-500">Isi jika tidak menggunakan kalkulasi otomatis.</div>
                </div>
                <div class="p-6 grid grid-cols-2 md:grid-cols-5 gap-6">
                    <div>
                        <label class="block text-sm font-medium">Fajr</label>
                        <input type="time" name="fajr_time" value="{{ old('fajr_time', optional($prayer)->fajr_time?->format('H:i')) }}" class="mt-2 w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Dhuhr</label>
                        <input type="time" name="dhuhr_time" value="{{ old('dhuhr_time', optional($prayer)->dhuhr_time?->format('H:i')) }}" class="mt-2 w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Asr</label>
                        <input type="time" name="asr_time" value="{{ old('asr_time', optional($prayer)->asr_time?->format('H:i')) }}" class="mt-2 w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Maghrib</label>
                        <input type="time" name="maghrib_time" value="{{ old('maghrib_time', optional($prayer)->maghrib_time?->format('H:i')) }}" class="mt-2 w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Isha</label>
                        <input type="time" name="isha_time" value="{{ old('isha_time', optional($prayer)->isha_time?->format('H:i')) }}" class="mt-2 w-full">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    Simpan pengaturan
                </button>
            </div>
        </form>

        @if(optional($display)->logo_path)
            <form id="delete-logo-form" method="POST" action="{{ route('settings.deleteLogo') }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif

        @if(optional($display)->wallpaper_paths)
            @foreach($display->wallpaper_paths as $wp)
                <form id="delete-wallpaper-{{ md5($wp) }}" method="POST" action="{{ route('settings.deleteWallpaper') }}" class="hidden">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="path" value="{{ $wp }}">
                </form>
            @endforeach
        @endif
    </div>
</x-app-layout>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('btn-v3-search');
  const input = document.getElementById('v3-city-search');
  const select = document.getElementById('v3-city-results');
  const idField = document.querySelector('input[name="myquran_v3_city_id"]');
  if (!btn || !input || !select || !idField) return;
  btn.addEventListener('click', async () => {
    const q = (input.value || '').trim();
    if (!q) return;
    select.innerHTML = '<option value="">Memuat…</option>';
    try {
      const res = await fetch(`https://api.myquran.com/v3/sholat/kabkota/cari/${encodeURIComponent(q)}`);
      if (!res.ok) { select.innerHTML = '<option value="">Gagal memuat</option>'; return; }
      const data = await res.json();
      const list = (data && data.data) ? data.data : [];
      const opts = ['<option value="">Pilih hasil…</option>'].concat(list.map(item => `<option value="${item.id}">${item.lokasi}</option>`));
      select.innerHTML = opts.join('');
    } catch (e) {
      select.innerHTML = '<option value="">Gagal memuat</option>';
    }
  });
  select.addEventListener('change', () => {
    const v = select.value;
    if (v) {
      idField.value = v;
    }
  });
});
</script>
