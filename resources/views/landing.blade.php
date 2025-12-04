<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MasjidDigital - Solusi Digital untuk Masjid Modern</title>
    <meta name="description" content="Sistem informasi digital masjid yang lengkap dengan jam digital, jadwal shalat, info kajian, transparansi keuangan, dan info khutbah Jumat dalam satu platform">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --teal-50: #f0fdfa;
            --teal-100: #ccfbf1;
            --teal-200: #a7f3d0;
            --teal-500: #14b8a6;
            --teal-600: #0d9488;
            --teal-700: #0f766e;
            --teal-800: #115e59;
            --cyan-50: #ecfeff;
            --cyan-200: #a5f3fc;
            --cyan-600: #0891b2;
            --cyan-700: #0e7490;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            line-height: 1.6;
            color: var(--gray-900);
            background: linear-gradient(to bottom, #ffffff, var(--teal-50));
            min-height: 100vh;
        }
        html { scroll-behavior: smooth; }
        .pattern-overlay {
            position: fixed; inset: 0; opacity: .05; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23047857' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            background-size: 60px 60px; z-index: 0;
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
        header { position: sticky; top: 0; background: rgba(255,255,255,0.8); backdrop-filter: blur(12px); border-bottom: 1px solid var(--teal-100); z-index: 100; }
        nav { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; }
        .logo { display: flex; align-items: center; gap: .5rem; text-decoration: none; }
        .logo-icon { width: 40px; height: 40px; background: linear-gradient(135deg, var(--teal-500), var(--cyan-600)); border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .logo-text { font-size: 1.5rem; font-weight: bold; color: var(--teal-800); }
        .nav-links { display: flex; align-items: center; gap: 2rem; list-style: none; }
        .nav-links a { color: var(--gray-700); text-decoration: none; font-weight: 500; transition: color .3s ease; }
        .nav-links a:hover { color: var(--teal-600); }
        .btn { padding: .75rem 1.5rem; border-radius: 8px; font-weight: 600; text-decoration: none; transition: all .3s ease; cursor: pointer; border: none; display: inline-block; }
        .btn-primary { background: var(--teal-600); color: #fff; }
        .btn-primary:hover { background: var(--teal-700); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(13,148,136,.3); }
        .btn-outline { border: 2px solid var(--teal-600); color: var(--teal-600); background: transparent; }
        .btn-outline:hover { background: var(--teal-50); transform: translateY(-2px); }
        .btn-lg { padding: 1rem 2rem; font-size: 1.125rem; }
        .mobile-menu-btn { display: none; background: none; border: none; font-size: 1.5rem; color: var(--gray-700); cursor: pointer; }
        .hero { position: relative; padding: 5rem 1rem; text-align: center; }
        .hero-badge { display: inline-block; background: var(--teal-100); color: var(--teal-700); padding: .5rem 1rem; border-radius: 50px; font-size: .875rem; font-weight: 600; margin-bottom: 1.5rem; }
        .hero h1 { font-size: 3.5rem; font-weight: bold; line-height: 1.2; margin-bottom: 1.5rem; }
        .hero h1 .highlight { color: var(--teal-600); }
        .hero p { font-size: 1.25rem; color: var(--gray-600); max-width: 700px; margin: 0 auto 2.5rem; }
        .hero-buttons { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .decorative-circle { position: absolute; border-radius: 50%; opacity: .2; filter: blur(60px); }
        .circle-1 { top: 5rem; left: 5rem; width: 80px; height: 80px; background: var(--teal-200); }
        .circle-2 { bottom: 5rem; right: 5rem; width: 128px; height: 128px; background: var(--cyan-200); }
        .features { padding: 5rem 1rem; background: #fff; }
        .section-header { text-align: center; margin-bottom: 4rem; }
        .section-header h2 { font-size: 3rem; font-weight: bold; margin-bottom: 1rem; }
        .section-header p { font-size: 1.25rem; color: var(--gray-600); max-width: 700px; margin: 0 auto; }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; max-width: 1200px; margin: 0 auto; }
        .feature-card { background: #fff; border: 1px solid var(--teal-100); border-radius: 12px; padding: 2rem; transition: all .3s ease; }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,.1); }
        .feature-icon { width: 48px; height: 48px; background: var(--teal-100); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; transition: background .3s ease; }
        .feature-card:hover .feature-icon { background: var(--teal-600); }
        .feature-card h3 { font-size: 1.25rem; margin-bottom: .5rem; }
        .feature-card p { color: var(--gray-600); }
        .benefits { padding: 5rem 1rem; background: linear-gradient(135deg, var(--teal-50), var(--cyan-50)); }
        .benefits-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; max-width: 1000px; margin: 0 auto; }
        .benefit-card { background: #fff; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,.05); transition: box-shadow .3s ease; }
        .benefit-card:hover { box-shadow: 0 12px 24px rgba(0,0,0,.1); }
        .benefit-icon { width: 56px; height: 56px; background: linear-gradient(135deg, var(--teal-500), var(--cyan-600)); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; }
        .how-it-works { padding: 5rem 1rem; background: #fff; }
        .steps-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 3rem; max-width: 900px; margin: 0 auto; }
        .step { text-align: center; }
        .step-number { width: 64px; height: 64px; background: var(--teal-600); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin: 0 auto 1rem; }
        .cta { padding: 5rem 1rem; background: linear-gradient(135deg, var(--teal-600), var(--cyan-700)); position: relative; overflow: hidden; }
        .cta::before { content: ''; position: absolute; inset: 0; opacity: .1; background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); background-size: 60px 60px; }
        .cta-content { position: relative; text-align: center; max-width: 800px; margin: 0 auto; }
        .cta h2 { font-size: 3rem; color: #fff; margin-bottom: 1.5rem; }
        .cta p { font-size: 1.25rem; color: rgba(255,255,255,.9); margin-bottom: 2.5rem; }
        .btn-white { background: #fff; color: var(--teal-600); }
        .btn-white:hover { background: var(--gray-100); transform: translateY(-2px); }
        footer { background: var(--gray-900); color: var(--gray-600); padding: 3rem 1rem 2rem; }
        .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin-bottom: 2rem; }
        .footer-brand { display: flex; align-items: center; gap: .5rem; margin-bottom: 1rem; }
        .footer-brand .logo-icon { width: 32px; height: 32px; }
        .footer-brand .logo-text { color: #fff; font-size: 1.25rem; }
        .footer-col h4 { color: #fff; font-weight: 600; margin-bottom: 1rem; }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: .5rem; }
        .footer-col a { color: var(--gray-600); text-decoration: none; transition: color .3s ease; }
        .footer-col a:hover { color: var(--teal-500); }
        .footer-bottom { border-top: 1px solid var(--gray-800); padding-top: 2rem; text-align: center; }
        .icon { width: 24px; height: 24px; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; fill: none; }
        @media (max-width: 768px) {
            .nav-links { display: none; position: absolute; top: 100%; left: 0; right: 0; background: #fff; flex-direction: column; padding: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,.1); }
            .nav-links.active { display: flex; }
            .mobile-menu-btn { display: block; }
            .hero h1 { font-size: 2rem; }
            .hero p { font-size: 1rem; }
            .section-header h2 { font-size: 2rem; }
            .cta h2 { font-size: 2rem; }
            .hero-buttons { flex-direction: column; }
            .features-grid, .benefits-grid, .steps-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="pattern-overlay"></div>
    <header>
        <nav class="container">
            <a href="#" class="logo">
                <div class="logo-icon">
                    <svg class="icon" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <span class="logo-text">MasjidDigital</span>
            </a>
            <ul class="nav-links" id="navLinks">
                <li><a href="#fitur">Fitur</a></li>
                <li><a href="#manfaat">Manfaat</a></li>
                <li><a href="#tentang">Tentang</a></li>
                <li><a href="#daftar" class="btn btn-primary">Daftar Sekarang</a></li>
            </ul>
            <button class="mobile-menu-btn" id="mobileMenuBtn" onclick="toggleMenu()">☰</button>
        </nav>
    </header>
    <section class="hero">
        <div class="container">
            <div class="hero-badge">Solusi Digital untuk Masjid Modern</div>
            <h1>Wujudkan Masjid yang<br><span class="highlight">Transparan & Terhubung</span></h1>
            <p>Sistem informasi digital masjid yang lengkap dengan jam digital, jadwal shalat, info kajian, transparansi keuangan, dan info khutbah Jumat dalam satu platform</p>
            <div class="hero-buttons">
                <a href="{{ route('public.mosques.create') }}" class="btn btn-primary btn-lg">Mulai Gratis</a>
                <a href="{{ route('display.show', ['slug' => 'masjid-contoh']) }}" class="btn btn-outline btn-lg">Lihat Demo</a>
            </div>
        </div>
        <div class="decorative-circle circle-1"></div>
        <div class="decorative-circle circle-2"></div>
    </section>
    <section id="fitur" class="features">
        <div class="container">
            <div class="section-header">
                <h2>Fitur Lengkap untuk Masjid</h2>
                <p>Semua yang Anda butuhkan untuk mengelola informasi masjid dalam satu platform</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <h3>Jam Digital Masjid</h3>
                    <p>Tampilan jam digital yang jelas dan elegan untuk masjid Anda dengan desain yang modern</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg class="icon" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                    <h3>Jadwal Shalat Otomatis</h3>
                    <p>Jadwal waktu shalat yang akurat dan terupdate secara otomatis sesuai lokasi masjid</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    </div>
                    <h3>Info Kajian</h3>
                    <p>Kelola dan tampilkan informasi kajian rutin masjid dengan mudah</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg class="icon" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    </div>
                    <h3>Transparansi Keuangan</h3>
                    <p>Tampilkan laporan keuangan masjid secara transparan kepada jamaah</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    </div>
                    <h3>Info Khutbah Jumat</h3>
                    <p>Bagikan tema dan informasi khutbah Jumat kepada jamaah</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    </div>
                    <h3>Panel Admin Lengkap</h3>
                    <p>Kelola semua konten display dengan mudah melalui dashboard admin yang intuitif</p>
                </div>
            </div>
        </div>
    </section>
    <section id="manfaat" class="benefits">
        <div class="container">
            <div class="section-header">
                <h2>Manfaat untuk Masjid Anda</h2>
                <p>Tingkatkan kualitas pelayanan dan transparansi masjid</p>
            </div>
            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon"><svg class="icon" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg></div>
                    <h3>Akses Mudah</h3>
                    <p>Jamaah dapat mengakses informasi masjid kapan saja melalui perangkat mereka</p>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon"><svg class="icon" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></div>
                    <h3>Meningkatkan Keterlibatan</h3>
                    <p>Tingkatkan partisipasi jamaah dengan informasi yang selalu terkini</p>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon"><svg class="icon" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></div>
                    <h3>Aman & Terpercaya</h3>
                    <p>Data masjid Anda dijaga dengan sistem keamanan yang terpercaya</p>
                </div>
            </div>
        </div>
    </section>
    <section id="tentang" class="how-it-works">
        <div class="container">
            <div class="section-header">
                <h2>Cara Kerja</h2>
                <p>Mulai dalam 3 langkah mudah</p>
            </div>
            <div class="steps-grid">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Daftar Akun</h3>
                    <p>Buat akun untuk masjid Anda dengan mudah</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Kelola Konten</h3>
                    <p>Atur jadwal, info kajian, dan keuangan dari dashboard admin</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Tampilkan & Bagikan</h3>
                    <p>Display digital aktif dan jamaah dapat akses informasi</p>
                </div>
            </div>
        </div>
    </section>
    <section id="daftar" class="cta">
        <div class="cta-content">
            <h2>Siap Modernisasi Masjid Anda?</h2>
            <p>Bergabung dengan ratusan masjid yang telah menggunakan platform kami untuk meningkatkan pelayanan kepada jamaah</p>
            <a href="{{ route('public.mosques.create') }}" class="btn btn-white btn-lg">Daftar Sekarang - Gratis</a>
        </div>
    </section>
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="footer-brand">
                        <div class="logo-icon">
                            <svg class="icon" viewBox="0 0 24 24" style="stroke: white;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <span class="logo-text">MasjidDigital</span>
                    </div>
                    <p>Solusi digital terpadu untuk masjid modern</p>
                </div>
                <div class="footer-col">
                    <h4>Produk</h4>
                    <ul>
                        <li><a href="#fitur">Fitur</a></li>
                        <li><a href="{{ route('display.show', ['slug' => 'masjid-contoh']) }}">Demo</a></li>
                        <li><a href="#harga">Harga</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Perusahaan</h4>
                    <ul>
                        <li><a href="#tentang">Tentang Kami</a></li>
                        <li><a href="#kontak">Kontak</a></li>
                        <li><a href="#blog">Blog</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="#privasi">Privasi</a></li>
                        <li><a href="#syarat">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© {{ date('Y') }} MasjidDigital. Seluruh hak cipta.</p>
            </div>
        </div>
    </footer>
    <script>
        function toggleMenu() { const navLinks = document.getElementById('navLinks'); navLinks.classList.toggle('active'); }
        document.addEventListener('click', function(event) { const navLinks = document.getElementById('navLinks'); const menuBtn = document.getElementById('mobileMenuBtn'); if (!navLinks.contains(event.target) && !menuBtn.contains(event.target)) { navLinks.classList.remove('active'); } });
        document.querySelectorAll('.nav-links a').forEach(function(link){ link.addEventListener('click', function(){ document.getElementById('navLinks').classList.remove('active'); }); });
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor){ anchor.addEventListener('click', function(e){ const href=this.getAttribute('href'); if(href!=="#" && href.startsWith('#')){ e.preventDefault(); const target=document.querySelector(href); if(target){ target.scrollIntoView({ behavior:'smooth', block:'start' }); } } }); });
    </script>
</body>
</html>
