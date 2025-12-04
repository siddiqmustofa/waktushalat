<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0 text-truncate" style="max-width: 70%">Pengaturan Display & Jadwal</h6>
            <span class="text-sm text-muted d-none d-sm-inline">Atur media, timer, sholat</span>
        </div>
    </x-slot>

    <div class="container-fluid py-3 py-md-4">
        @if(session('status'))
            <div class="mx-6">
                <div class="rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 px-4 py-3">{{ session('status') }}</div>
            </div>
        @endif
        <form action="{{ route('settings.store') }}" method="post" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="card">
                <div class="card-header pb-0">
                    <h6>Media</h6>
                    <p class="text-sm">Logo dan wallpaper untuk slideshow latar.</p>
                </div>
                <div class="card-body">
                    <div class="row g-3 g-md-4">
                    <div>
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" accept="image/*" class="form-control">
                        @if(optional($display)->logo_path)
                            <div class="mt-3">
                                <img src="{{ asset('storage/' . $display->logo_path) }}" class="img-fluid rounded border" style="max-height: 64px" alt="logo">
                                <button type="submit" form="delete-logo-form" class="btn btn-sm btn-danger mt-2">Hapus logo</button>
                            </div>
                        @endif
                    </div>
                    <div>
                        <label class="form-label">Wallpapers</label>
                        <input type="file" name="wallpapers[]" multiple accept="image/*" class="form-control">
                        @if(optional($display)->wallpaper_paths)
                            <div class="mt-3 row row-cols-2 row-cols-md-5 g-2 g-md-3">
                                @foreach($display->wallpaper_paths as $wp)
                                    <div>
                                        <img src="{{ asset('storage/' . $wp) }}" class="img-fluid rounded border" style="height: 80px; width: 100%; object-fit: cover" alt="wp">
                                        <button type="submit" form="delete-wallpaper-{{ md5($wp) }}" class="btn btn-sm btn-danger w-100 mt-1">Hapus</button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <h6>Timer</h6>
                    <p class="text-sm">Durasi slideshow, jeda adzan, dan durasi adzan/sholat.</p>
                </div>
                <div class="card-body">
                    <div class="row g-3 g-md-4">
                    <div>
                        <label class="form-label">Durasi pengumuman (detik)</label>
                        <input type="number" name="info_seconds" value="{{ old('info_seconds', optional($display)->info_seconds) }}" class="form-control" placeholder="5">
                    </div>
                    <div>
                        <label class="form-label">Detik wallpaper</label>
                        <input type="number" name="wallpaper_seconds" value="{{ old('wallpaper_seconds', optional($display)->wallpaper_seconds) }}" class="form-control" placeholder="15">
                    </div>
                    <div>
                        <label class="form-label">Menit sebelum adzan</label>
                        <input type="number" name="wait_adzan_minutes" value="{{ old('wait_adzan_minutes', optional($display)->wait_adzan_minutes) }}" class="form-control" placeholder="1">
                    </div>
                    <div>
                        <label class="form-label">Menit durasi adzan</label>
                        <input type="number" name="adzan_minutes" value="{{ old('adzan_minutes', optional($display)->adzan_minutes) }}" class="form-control" placeholder="3">
                    </div>
                    <div>
                        <label class="form-label">Menit durasi sholat</label>
                        <input type="number" name="sholat_minutes" value="{{ old('sholat_minutes', optional($display)->sholat_minutes) }}" class="form-control" placeholder="20">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <h6>Durasi Iqomah</h6>
                    <p class="text-sm">Set durasi iqomah per waktu sholat.</p>
                </div>
                <div class="card-body">
                    <div class="row row-cols-2 row-cols-md-5 g-3 g-md-4">
                    <div>
                        <label class="form-label">Iqomah Subuh</label>
                        <input type="number" name="iqomah_minutes_fajr" value="{{ old('iqomah_minutes_fajr', optional($display)->iqomah_minutes['fajr'] ?? 10) }}" class="form-control">
                    </div>
                    <div>
                        <label class="form-label">Iqomah Dzuhur</label>
                        <input type="number" name="iqomah_minutes_dhuhr" value="{{ old('iqomah_minutes_dhuhr', optional($display)->iqomah_minutes['dhuhr'] ?? 10) }}" class="form-control">
                    </div>
                    <div>
                        <label class="form-label">Iqomah Ashar</label>
                        <input type="number" name="iqomah_minutes_asr" value="{{ old('iqomah_minutes_asr', optional($display)->iqomah_minutes['asr'] ?? 10) }}" class="form-control">
                    </div>
                    <div>
                        <label class="form-label">Iqomah Maghrib</label>
                        <input type="number" name="iqomah_minutes_maghrib" value="{{ old('iqomah_minutes_maghrib', optional($display)->iqomah_minutes['maghrib'] ?? 10) }}" class="form-control">
                    </div>
                    <div>
                        <label class="form-label">Iqomah Isya</label>
                        <input type="number" name="iqomah_minutes_isha" value="{{ old('iqomah_minutes_isha', optional($display)->iqomah_minutes['isha'] ?? 10) }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <h6>Jumat & Tarawih</h6>
                    <p class="text-sm">Opsi khusus untuk hari Jumat dan bulan Ramadhan.</p>
                </div>
                <div class="card-body">
                    <div class="row g-3 g-md-4">
                    <div class="flex items-center gap-3">
                        <input id="jumat_active" class="form-check-input" type="checkbox" name="jumat_active" value="1" {{ old('jumat_active', optional($display)->jumat_active) ? 'checked' : '' }}>
                        <label for="jumat_active" class="form-check-label">Jumat aktif</label>
                    </div>
                    <div>
                        <label class="form-label">Durasi khutbah (menit)</label>
                        <input type="number" name="jumat_duration_minutes" value="{{ old('jumat_duration_minutes', optional($display)->jumat_duration_minutes) }}" class="form-control">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Teks khutbah</label>
                        <input type="text" name="jumat_text" value="{{ old('jumat_text', optional($display)->jumat_text) }}" class="form-control" placeholder="Masukkan pesan kutbah singkat">
                    </div>
                    <div class="flex items-center gap-3">
                        <input id="tarawih_active" class="form-check-input" type="checkbox" name="tarawih_active" value="1" {{ old('tarawih_active', optional($display)->tarawih_active) ? 'checked' : '' }}>
                        <label for="tarawih_active" class="form-check-label">Tarawih aktif</label>
                    </div>
                    <div>
                        <label class="form-label">Durasi tarawih (menit)</label>
                        <input type="number" name="tarawih_duration_minutes" value="{{ old('tarawih_duration_minutes', optional($display)->tarawih_duration_minutes) }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <h6>Pengaturan Suara Adzan</h6>
                    <p class="text-sm">Aktif/nonaktifkan suara untuk tiap waktu sholat.</p>
                </div>
                <div class="card-body">
                    <div class="row row-cols-2 row-cols-md-5 g-3 g-md-4">
                    <div class="flex items-center gap-3">
                        <input id="sound_fajr" class="form-check-input" type="checkbox" name="sound_fajr" value="1" {{ old('sound_fajr', optional($display)->sound_fajr) ? 'checked' : '' }}>
                        <label for="sound_fajr" class="form-check-label">Subuh</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <input id="sound_dhuhr" class="form-check-input" type="checkbox" name="sound_dhuhr" value="1" {{ old('sound_dhuhr', optional($display)->sound_dhuhr) ? 'checked' : '' }}>
                        <label for="sound_dhuhr" class="form-check-label">Dzuhur</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <input id="sound_asr" class="form-check-input" type="checkbox" name="sound_asr" value="1" {{ old('sound_asr', optional($display)->sound_asr) ? 'checked' : '' }}>
                        <label for="sound_asr" class="form-check-label">Ashar</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <input id="sound_maghrib" class="form-check-input" type="checkbox" name="sound_maghrib" value="1" {{ old('sound_maghrib', optional($display)->sound_maghrib) ? 'checked' : '' }}>
                        <label for="sound_maghrib" class="form-check-label">Maghrib</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <input id="sound_isha" class="form-check-input" type="checkbox" name="sound_isha" value="1" {{ old('sound_isha', optional($display)->sound_isha) ? 'checked' : '' }}>
                        <label for="sound_isha" class="form-check-label">Isya</label>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <h6>Tampilkan Kartu</h6>
                    <p class="text-sm">Atur visibilitas kartu di halaman display.</p>
                </div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-2 g-3 g-md-4">
                    <div class="flex items-center gap-3">
                        <input id="show_finance_card" class="form-check-input" type="checkbox" name="show_finance_card" value="1" {{ old('show_finance_card', optional($display)->show_finance_card) ? 'checked' : '' }}>
                        <label for="show_finance_card" class="form-check-label">Saldo Kas Mesjid</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <input id="show_friday_officer_card" class="form-check-input" type="checkbox" name="show_friday_officer_card" value="1" {{ old('show_friday_officer_card', optional($display)->show_friday_officer_card) ? 'checked' : '' }}>
                        <label for="show_friday_officer_card" class="form-check-label">Petugas Jumat</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <input id="show_kajian_card" class="form-check-input" type="checkbox" name="show_kajian_card" value="1" {{ old('show_kajian_card', optional($display)->show_kajian_card) ? 'checked' : '' }}>
                        <label for="show_kajian_card" class="form-check-label">Jadwal Kajian</label>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <h6>Saldo Kas Mesjid</h6>
                    <p class="text-sm">Masukkan saldo terkini untuk ditampilkan di halaman display.</p>
                </div>
                <div class="card-body">
                    <div class="row g-3 g-md-4">
                    <div>
                        <label class="form-label">Infaq Jumat</label>
                        <input type="number" step="0.01" name="infaq_jumat" value="{{ old('infaq_jumat', optional($display)->infaq_jumat) }}" class="form-control" placeholder="0">
                    </div>
                    <div>
                        <label class="form-label">Infaq</label>
                        <input type="number" step="0.01" name="infaq_langsung" value="{{ old('infaq_langsung', optional($display)->infaq_langsung) }}" class="form-control" placeholder="0">
                    </div>
                    <div>
                        <label class="form-label">Saldo Kas</label>
                        <input type="number" step="0.01" name="saldo_kas" value="{{ old('saldo_kas', optional($display)->saldo_kas) }}" class="form-control" placeholder="0">
                    </div>
                    <div>
                        <label class="form-label">Saldo Bank</label>
                        <input type="number" step="0.01" name="saldo_bank_syariah" value="{{ old('saldo_bank_syariah', optional($display)->saldo_bank_syariah) }}" class="form-control" placeholder="0">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <h6>Kalkulasi Otomatis</h6>
                    <p class="text-sm">Pilih antara koordinat atau API MyQuran.</p>
                </div>
                <div class="card-body">
                    <div class="row g-3 g-md-4">
                    <div>
                        <label class="form-label">Metode kalkulasi</label>
                        <select name="calculation_method" class="form-select">
                            @php($methods = ['0'=>'Manual','MyQuranV3'=>'MyQuran v3 (API)'])
                            @foreach($methods as $k=>$v)
                                <option value="{{ $k }}" @selected(old('calculation_method', optional($prayer)->calculation_method) == $k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">MyQuran v3 City ID</label>
                        <input type="text" name="myquran_v3_city_id" value="{{ old('myquran_v3_city_id', optional($prayer)->myquran_v3_city_id) }}" class="form-control" placeholder="eda80a3d5b344bc40f3bc04f65b7a357">
                        <div class="form-text">Gunakan ID unik kota dari MyQuran v3.</div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Cari Kota (MyQuran v3)</label>
                        <div class="d-flex gap-2">
                            <input type="text" id="v3-city-search" class="form-control" placeholder="bandung">
                            <button type="button" id="btn-v3-search" class="btn btn-primary">Cari</button>
                        </div>
                        <select id="v3-city-results" class="form-select mt-2">
                            <option value="">Pilih hasil…</option>
                        </select>
                        <div class="form-text">Pilih kab/kota untuk mengisi MyQuran v3 City ID.</div>
                    </div>
                    <div>
                        <label class="form-label">Timezone (IANA)</label>
                        <input type="text" name="timezone" value="{{ old('timezone', optional($prayer)->timezone) }}" class="form-control" placeholder="Asia/Jakarta">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <h6>Waktu Sholat Manual</h6>
                    <p class="text-sm">Isi jika tidak menggunakan kalkulasi otomatis.</p>
                </div>
                <div class="card-body">
                    <div class="row row-cols-2 row-cols-md-5 g-3 g-md-4">
                    <div>
                        <label class="form-label">Fajr</label>
                        <input type="time" name="fajr_time" value="{{ old('fajr_time', optional($prayer)->fajr_time?->format('H:i')) }}" class="form-control">
                    </div>
                    <div>
                        <label class="form-label">Dhuhr</label>
                        <input type="time" name="dhuhr_time" value="{{ old('dhuhr_time', optional($prayer)->dhuhr_time?->format('H:i')) }}" class="form-control">
                    </div>
                    <div>
                        <label class="form-label">Asr</label>
                        <input type="time" name="asr_time" value="{{ old('asr_time', optional($prayer)->asr_time?->format('H:i')) }}" class="form-control">
                    </div>
                    <div>
                        <label class="form-label">Maghrib</label>
                        <input type="time" name="maghrib_time" value="{{ old('maghrib_time', optional($prayer)->maghrib_time?->format('H:i')) }}" class="form-control">
                    </div>
                    <div>
                        <label class="form-label">Isha</label>
                        <input type="time" name="isha_time" value="{{ old('isha_time', optional($prayer)->isha_time?->format('H:i')) }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button class="btn btn-primary">Simpan pengaturan</button>
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
