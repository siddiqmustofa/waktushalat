<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0 text-truncate" style="max-width: 70%">Dashboard Super Admin</h6>
            <span class="text-sm text-muted d-none d-sm-inline">Ringkasan status sistem</span>
        </div>
    </x-slot>

    <div class="container-fluid py-3 py-md-4">
        <div class="row g-3 g-md-4">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape bg-primary text-white rounded-circle me-3" style="width:44px;height:44px;display:flex;align-items:center;justify-content:center">
                                <i class="ni ni-building"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Jumlah Masjid</h6>
                                <div class="display-6 fw-bold">{{ $total }}</div>
                                <p class="text-sm text-muted mb-0">Total terdaftar di sistem</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape bg-success text-white rounded-circle me-3" style="width:44px;height:44px;display:flex;align-items:center;justify-content:center">
                                <i class="ni ni-check-bold"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Masjid Online</h6>
                                <div class="display-6 fw-bold">{{ $online }}</div>
                                <p class="text-sm text-muted mb-0">Status aktif (is_active)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-header pb-0">
                        <h6>Info Sistem</h6>
                        <p class="text-sm">Dummy informasi untuk tampilan awal.</p>
                    </div>
                    <div class="card-body">
                        <ul class="text-sm text-muted">
                            <li>Registrasi publik tersedia.</li>
                            <li>Pengaturan display dan jadwal aktif.</li>
                            <li>Halaman admin masjid lengkap.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

