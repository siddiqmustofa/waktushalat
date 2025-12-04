<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0 text-truncate" style="max-width: 70%">Dashboard</h6>
            <span class="text-sm text-muted d-none d-sm-inline">Ringkasan cepat</span>
        </div>
    </x-slot>

    <div class="container-fluid">
        <div class="row gx-2 gx-md-4 gy-2 gy-md-4">
            <div class="col-12 col-sm-6 col-xl-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-sm mb-0 text-capitalize">Status</p>
                                <h6 class="mb-0">Aktif</h6>
                            </div>
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;">
                                <i class="ni ni-check-bold text-white" style="font-size: .95rem;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <p class="text-sm text-muted mb-0 text-truncate">You're logged in!</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-header pb-0">
                        <p class="text-sm mb-0 text-capitalize">Masjid</p>
                        <h6 class="mb-0 text-truncate" style="max-width: 70vw">{{ auth()->user()->mosque?->name ?? 'Belum terhubung' }}</h6>
                    </div>
                    <div class="card-body pt-0">
                        <p class="text-sm text-muted mb-0 text-truncate">Timezone: {{ auth()->user()->mosque?->timezone ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-header pb-0">
                        <p class="text-sm mb-0 text-capitalize">Pengumuman</p>
                        <h6 class="mb-0">—</h6>
                    </div>
                    <div class="card-body pt-0">
                        <p class="text-sm text-muted mb-0 text-truncate">Navigasi dari sidebar untuk mengelola.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-header pb-0">
                        <p class="text-sm mb-0 text-capitalize">Display</p>
                        <h6 class="mb-0">{{ auth()->user()->mosque?->slug ? 'Tersedia' : 'Tidak tersedia' }}</h6>
                    </div>
                    <div class="card-body pt-0">
                        @if(auth()->user()->mosque?->slug)
                            <a class="btn btn-sm btn-primary w-100 w-sm-auto" target="_blank" href="{{ url('/m/' . auth()->user()->mosque->slug) }}">Buka Display</a>
                        @else
                            <a class="btn btn-sm btn-outline-primary w-100 w-sm-auto" href="{{ route('public.mosques.create') }}">Registrasi Masjid</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-2 g-md-4 mt-1">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Selamat datang</h6>
                        <p class="text-sm">Gunakan menu di kiri untuk mulai mengelola konten.</p>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 g-md-3">
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md me-3">
                                        <i class="ni ni-settings-gear-65 text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Pengaturan</h6>
                                        <p class="text-sm text-muted mb-0">Atur jadwal shalat dan tampilan.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md me-3">
                                        <i class="ni ni-notification-70 text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Pengumuman</h6>
                                        <p class="text-sm text-muted mb-0">Kelola info kegiatan dan pengumuman.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
