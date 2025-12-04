<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mesjid Display</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/nucleo/2.0.6/css/nucleo.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css" rel="stylesheet" />
    <link id="pagestyle" href="https://cdn.jsdelivr.net/npm/argon-dashboard@2.0.5/assets/css/argon-dashboard.css" rel="stylesheet" />
</head>
<body class="g-sidenav-show bg-gray-100">
    <main class="main-content position-relative border-radius-lg">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <h6 class="mb-0">Mesjid Display</h6>
                    <span class="text-sm text-muted">Solusi tampilan informasi masjid</span>
                </nav>
                <div class="ms-md-auto pe-md-3 d-flex align-items-center gap-2">
                    <a class="btn btn-sm btn-dark mb-0" href="{{ route('login') }}">Masuk</a>
                    <a class="btn btn-sm btn-primary mb-0" href="{{ route('public.mosques.create') }}">Registrasi Masjid</a>
                </div>
            </div>
        </nav>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6>Selamat datang</h6>
                            <p class="text-sm">Platform untuk menampilkan informasi jadwal sholat, pengumuman, dan media masjid.</p>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 g-md-4">
                                <div class="col-12 col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape bg-primary text-white rounded-circle me-3" style="width:44px;height:44px;display:flex;align-items:center;justify-content:center">
                                            <i class="ni ni-calendar-grid-58"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Jadwal Sholat & Iqomah</h6>
                                            <p class="text-sm text-muted mb-0">Kelola waktu sholat manual atau otomatis via API.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape bg-success text-white rounded-circle me-3" style="width:44px;height:44px;display:flex;align-items:center;justify-content:center">
                                            <i class="ni ni-notification-70"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Pengumuman & Running Text</h6>
                                            <p class="text-sm text-muted mb-0">Sampaikan informasi penting kepada jamaah.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape bg-warning text-white rounded-circle me-3" style="width:44px;height:44px;display:flex;align-items:center;justify-content:center">
                                            <i class="ni ni-image"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Media & Wallpaper</h6>
                                            <p class="text-sm text-muted mb-0">Atur logo dan slideshow wallpaper.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape bg-info text-white rounded-circle me-3" style="width:44px;height:44px;display:flex;align-items:center;justify-content:center">
                                            <i class="ni ni-user-run"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Petugas Jum'at & Kajian</h6>
                                            <p class="text-sm text-muted mb-0">Kelola petugas Jumat dan jadwal kajian.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3 mt-md-4">
                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                            <h6>Tentang Platform</h6>
                            <p class="text-sm">Informasi dummy untuk memperlihatkan gaya Argon.</p>
                        </div>
                        <div class="card-body">
                            <p class="text-sm text-muted">Platform ini mendukung pengelolaan jadwal sholat, iqomah, pengumuman, running text, media slideshow, petugas Jumat, dan jadwal kajian. Tampilan display dirancang agar mudah dibaca di layar besar.</p>
                            <ul class="text-sm text-muted">
                                <li>Integrasi API MyQuran v3 untuk jadwal otomatis</li>
                                <li>Kontrol durasi slideshow dan adzan/sholat</li>
                                <li>Manajemen media: logo dan wallpaper</li>
                                <li>Kartu keuangan dan informasi tambahan</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-header pb-0 d-flex align-items-center justify-content-between">
                            <h6>Mulai Sekarang</h6>
                            <div class="d-none d-sm-flex gap-2">
                                <a class="btn btn-sm btn-dark" href="{{ route('login') }}">Masuk</a>
                                <a class="btn btn-sm btn-primary" href="{{ route('public.mosques.create') }}">Registrasi</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-sm text-muted">Buat akun masjid Anda atau masuk untuk mulai mengelola konten display. Proses registrasi sederhana dan cepat.</p>
                            <div class="d-grid gap-2 d-sm-none">
                                <a class="btn btn-dark" href="{{ route('login') }}">Masuk</a>
                                <a class="btn btn-primary" href="{{ route('public.mosques.create') }}">Registrasi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
