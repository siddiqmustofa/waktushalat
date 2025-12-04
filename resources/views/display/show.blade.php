<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $mosque->name }}</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css">
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('displayApp', () => ({
                now: new Date(),
                dateText: '',
                timeText: '',
                phase: null,
                phaseKey: null,
                activeKey: null,
                idxQuote: 0,
                quotes: @json($mosque->announcements->map(fn($a) => [
                    't1' => $a->title,
                    't2' => $a->body,
                    't3' => ''
                ])),
                times: {
                    fajr: '{{ optional($prayer)->fajr_time?->format('H:i') ?? '' }}',
                    dhuhr: '{{ optional($prayer)->dhuhr_time?->format('H:i') ?? '' }}',
                    asr: '{{ optional($prayer)->asr_time?->format('H:i') ?? '' }}',
                    maghrib: '{{ optional($prayer)->maghrib_time?->format('H:i') ?? '' }}',
                    isha: '{{ optional($prayer)->isha_time?->format('H:i') ?? '' }}'
                },
                wallpapers: {!! json_encode(collect(optional($mosque->displaySetting)->wallpaper_paths ?? [])->map(fn($p) => asset('storage/' . $p))->values()) !!},
                wpIndex: 0,
                currentWallpaperUrl: '',
                timers: {
                    info: {{ (int)optional($mosque->displaySetting)->info_seconds ?? 5 }},
                    wallpaper: {{ (int)optional($mosque->displaySetting)->wallpaper_seconds ?? 15 }},
                    wait_adzan: {{ (int)optional($mosque->displaySetting)->wait_adzan_minutes ?? 1 }},
                    adzan: {{ (int)optional($mosque->displaySetting)->adzan_minutes ?? 3 }},
                    sholat: {{ (int)optional($mosque->displaySetting)->sholat_minutes ?? 20 }},
                    iqomah: {!! json_encode(optional($mosque->displaySetting)->iqomah_minutes ?? ['fajr'=>10,'dhuhr'=>10,'asr'=>10,'maghrib'=>10,'isha'=>10]) !!}
                },
                countdown: null,
                finances: { infaqJumat: {{ (float) (optional($display)->infaq_jumat ?? 0) }}, infaqLangsung: {{ (float) (optional($display)->infaq_langsung ?? 0) }}, saldoKas: {{ (float) (optional($display)->saldo_kas ?? 0) }}, saldoBank: {{ (float) (optional($display)->saldo_bank_syariah ?? 0) }} },
                formatRupiah(n){ try { return 'Rp ' + Number(n||0).toLocaleString('id-ID'); } catch(e) { return 'Rp 0'; } },
                init() {
                    this.tick();
                    setInterval(() => this.tick(), 1000);
                    setInterval(() => this.rotateQuote(), this.timers.info * 1000);
                    this.setupCountdowns();
                    this.setupWallpapers();
                    this.setupAutoRefresh();
                },
                tick() {
                    this.now = new Date();
                    const d = this.now;
                    const hari = d.toLocaleDateString('id-ID', { weekday: 'long' });
                    const tanggalLengkap = d.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
                    this.dateText = `${hari}, ${tanggalLengkap}`;
                    const h = String(d.getHours()).padStart(2,'0');
                    const m = String(d.getMinutes()).padStart(2,'0');
                    const s = String(d.getSeconds()).padStart(2,'0');
                    this.timeText = `${h}.${m}<div>${s}</div>`;
                    this.computeActive();
                    this.setupCountdowns();
                },
                parseToday(timeStr) {
                    if (!timeStr) return null;
                    const [hh, mm] = timeStr.split(':');
                    const d = new Date();
                    d.setHours(parseInt(hh), parseInt(mm), 0, 0);
                    return d;
                },
                computeActive() {
                    const delayMs = 5 * 60 * 1000; // 5 menit
                    const tNowDelay = new Date(this.now.getTime() - delayMs);
                    const order = ['fajr','dhuhr','asr','maghrib','isha'];
                    let active = null;
                    for (const k of order) {
                        const t = this.parseToday(this.times[k]);
                        if (t && tNowDelay >= t) active = k;
                    }
                    this.activeKey = active;
                },
                setupCountdowns() {
                    const order = ['fajr','dhuhr','asr','maghrib','isha'];
                    let np = null;
                    let nk = null;
                    for (const k of order) {
                        const t = this.parseToday(this.times[k]);
                        if (!t) continue;
                        const waitStart = new Date(t.getTime() - this.timers.wait_adzan * 60 * 1000);
                        const adzanEnd = new Date(t.getTime() + this.timers.adzan * 60 * 1000);
                        const iqomahEnd = new Date(adzanEnd.getTime() + (this.timers.iqomah[k] || 0) * 60 * 1000);
                        const sholatEnd = new Date(iqomahEnd.getTime() + (this.timers.sholat || 0) * 60 * 1000);
                        if (this.now >= waitStart && this.now < t) { np = 'before'; nk = k; break; }
                        if (this.now >= t && this.now < adzanEnd) { np = 'adzan'; nk = k; break; }
                        if (this.now >= adzanEnd && this.now < iqomahEnd) { np = 'iqomah'; nk = k; break; }
                        if (this.now >= iqomahEnd && this.now < sholatEnd) { np = 'shalat'; nk = k; break; }
                    }
                    if (np === this.phase && nk === this.phaseKey) return;
                    this.phase = np;
                    this.phaseKey = nk;
                    const cdEl = document.getElementById('count-down');
                    const adzEl = document.getElementById('display-adzan');
                    const iqEl = document.getElementById('iqomah-banner');
                    const shoEl = document.getElementById('display-shalat');
                    if (!np) {
                        if (cdEl) cdEl.style.display = 'none';
                        if (adzEl) adzEl.style.display = 'none';
                        if (iqEl) iqEl.style.display = 'none';
                        if (shoEl) shoEl.style.display = 'none';
                        return;
                    }
                    const t = this.parseToday(this.times[nk]);
                    const adzanEnd = new Date(t.getTime() + this.timers.adzan * 60 * 1000);
                    const iqomahEnd = new Date(adzanEnd.getTime() + (this.timers.iqomah[nk] || 0) * 60 * 1000);
                    const sholatEnd = new Date(iqomahEnd.getTime() + (this.timers.sholat || 0) * 60 * 1000);
                    if (np === 'before') {
                        this.startCountdown(t, `Menuju ${nk}`);
                        if (shoEl) shoEl.style.display = 'none';
                    } else if (np === 'adzan') {
                        this.fullscreenMessage('display-adzan', nk);
                        if (shoEl) shoEl.style.display = 'none';
                    } else if (np === 'iqomah') {
                        this.startCountdown(iqomahEnd, 'IQOMAH');
                        if (shoEl) shoEl.style.display = 'none';
                    } else if (np === 'shalat') {
                        if (cdEl) cdEl.style.display = 'none';
                        if (adzEl) adzEl.style.display = 'none';
                        if (iqEl) iqEl.style.display = 'none';
                        if (shoEl) {
                            const titleEl = shoEl.querySelector('.sholat-title');
                            const subEl = shoEl.querySelector('.sholat-sub');
                            if (titleEl) titleEl.innerText = 'SHOLAT BERLANGSUNG';
                            if (subEl) subEl.innerText = 'Mohon tenang dan matikan HP';
                            shoEl.style.display = 'block';
                        }
                    }
                },
                setupWallpapers() {
                    if (!this.wallpapers || !this.wallpapers.length) return;
                    const el = document.querySelector('.bg-slideshow');
                    this.currentWallpaperUrl = this.wallpapers[this.wpIndex % this.wallpapers.length];
                    setInterval(() => {
                        if (el) el.classList.add('fade-out');
                        setTimeout(() => {
                            this.wpIndex = (this.wpIndex + 1) % this.wallpapers.length;
                            this.currentWallpaperUrl = this.wallpapers[this.wpIndex];
                            if (el) el.classList.remove('fade-out');
                        }, 500);
                    }, this.timers.wallpaper * 1000);
                },
                startCountdown(targetTime, title) {
                    const isIqomah = title && title.toUpperCase() === 'IQOMAH';
                    const el = document.getElementById(isIqomah ? 'iqomah-banner' : 'count-down');
                    const counter = el.querySelector('.counter');
                    el.style.display = 'block';
                    counter.querySelector('h1').innerText = title.toUpperCase();
                    this.countdown && clearInterval(this.countdown);
                    const update = () => {
                        const now = new Date();
                        const diff = Math.max(0, targetTime.getTime() - now.getTime());
                        const hh = String(Math.floor(diff / 3600000)).padStart(2,'0');
                        const ii = String(Math.floor((diff % 3600000) / 60000)).padStart(2,'0');
                        const ss = String(Math.floor((diff % 60000) / 1000)).padStart(2,'0');
                        counter.querySelector('.hh').innerHTML = `${hh}<span>JAM</span>`;
                        counter.querySelector('.ii').innerHTML = `${ii}<span>MENIT</span>`;
                        counter.querySelector('.ss').innerHTML = `${ss}<span>DETIK</span>`;
                        if (diff <= 0) {
                            clearInterval(this.countdown);
                            el.style.display = 'none';
                        }
                    };
                    update();
                    this.countdown = setInterval(update, 1000);
                },
                fullscreenMessage(id, text) {
                    const el = document.getElementById(id);
                    el.style.display = 'block';
                    const k = String(text).toLowerCase();
                    const labelMap = { fajr: 'ADZAN SUBUH', dhuhr: 'ADZAN DZUHUR', asr: 'ADZAN ASHAR', maghrib: 'ADZAN MAGHRIB', isha: 'ADZAN ISYA' };
                    const titleEl = el.querySelector('.azan-title');
                    const subEl = el.querySelector('.azan-sub');
                    if (titleEl) titleEl.innerText = labelMap[k] || String(text).toUpperCase();
                    const soundMap = {
                        fajr: {{ optional($display)->sound_fajr ? 'true' : 'false' }},
                        dhuhr: {{ optional($display)->sound_dhuhr ? 'true' : 'false' }},
                        asr: {{ optional($display)->sound_asr ? 'true' : 'false' }},
                        maghrib: {{ optional($display)->sound_maghrib ? 'true' : 'false' }},
                        isha: {{ optional($display)->sound_isha ? 'true' : 'false' }}
                    };
                    if (subEl) subEl.innerText = '';
                    this.playSound(text);
                    const t = this.parseToday(this.times[k]);
                    const iqomahEnd = t ? new Date(t.getTime() + this.timers.adzan * 60 * 1000 + (this.timers.iqomah[k] || 0) * 60 * 1000) : null;
                    setTimeout(() => {
                        el.style.display = 'none';
                        if (iqomahEnd) this.startCountdown(iqomahEnd, 'IQOMAH');
                    }, this.timers.adzan * 60 * 1000);
                },
                playSound(key){
                    const audio = document.getElementById('adzan-audio');
                    if (!audio) return;
                    const map = {
                        fajr: {{ optional($display)->sound_fajr ? 'true' : 'false' }},
                        dhuhr: {{ optional($display)->sound_dhuhr ? 'true' : 'false' }},
                        asr: {{ optional($display)->sound_asr ? 'true' : 'false' }},
                        maghrib: {{ optional($display)->sound_maghrib ? 'true' : 'false' }},
                        isha: {{ optional($display)->sound_isha ? 'true' : 'false' }},
                    };
                    if (!map[key]) return;
                    audio.currentTime = 0;
                    audio.play().catch(() => {});
                },
                rotateQuote() {
                    if (!this.quotes.length) return;
                    this.idxQuote = (this.idxQuote + 1) % this.quotes.length;
                },
                setupAutoRefresh(){
                    const slug = window.location.pathname.split('/').filter(Boolean).pop();
                    let lastHash = null;
                    const poll = async () => {
                        try {
                            const res = await fetch(`/m/${slug}/pulse`, { headers: { 'Accept': 'application/json' } });
                            if (!res.ok) return;
                            const data = await res.json();
                            if (lastHash && data.hash !== lastHash) {
                                window.location.reload();
                                return;
                            }
                            lastHash = data.hash;
                        } catch (e) {
                            // ignore
                        } finally {
                            setTimeout(poll, ({{ (int)optional($display)->refresh_seconds ?? 15 }}) * 1000);
                        }
                    };
                    poll();
                }
            }))
        })
    </script>
    <style>
        body { background: #0b1120; color: #e5e7eb; }
        .bg-slideshow { position: fixed; inset: 0; background-size: cover; background-position: center; z-index: -2; transition: background-image 1s ease-in-out, opacity .6s ease-in-out; }
        .bg-slideshow.fade-out { opacity: .2; }
        .display-overlay { position: fixed; inset: 0; background: radial-gradient(ellipse at center, rgba(0,0,0,0.4), rgba(0,0,0,0.75)); pointer-events: none; z-index: -1; }
        .layout { display: grid; grid-template-columns: 420px 1fr; gap: 1rem; align-items: start; }
        .clock { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; font-size: 6rem; line-height: 1; letter-spacing: -0.02em; }
        .clock div { display: inline-block; font-size: 2rem; margin-left: .5rem; opacity: .85; }
        .left-panel { background: rgba(15,23,42,.7); border-radius: 1rem; padding: 1.25rem; box-shadow: 0 10px 30px rgba(0,0,0,.2); backdrop-filter: blur(2px); }
        .schedule-row { display:flex; justify-content:space-between; padding:.8rem 1rem; border-radius:.8rem; align-items:center; font-size:1.75rem; }
        .schedule-row.active { background: rgba(34,197,94,.18); color:#eafff5; border-left: 6px solid var(--primary-color, #6366f1); }
        .quote-area { display:flex; align-items:center; justify-content:center; min-height: 360px; }
        .quote-title { font-size: 1.75rem; font-weight: 800; color: #5eead4; text-align:center; }
        .quote-body { margin-top: .75rem; font-size: 1.125rem; line-height: 1.75rem; text-align:center; }
        @keyframes marquee { 0% { transform: translateX(100%); } 100% { transform: translateX(-100%); } }
        .animate-marquee { animation: marquee 35s linear infinite; }
        .full-screen { position: fixed; inset: 0; display:flex; align-items:center; justify-content:center; background: rgba(0,0,0,0.8); z-index: 50; }
        .counter { text-align:center; display:flex; flex-direction:column; align-items:center; }
        .counter h1 { font-size:2rem; margin-bottom:1rem; }
        .counter .hh, .counter .ii, .counter .ss { display:inline-block; font-size:3rem; margin:0 1rem; }
        .counter span { display:block; font-size:.875rem; opacity:.7; }
        .iqomah-banner { position: fixed; right: 2rem; bottom: 2rem; background: rgba(15,23,42,.9); border-radius: .8rem; padding: 1rem 1.25rem; box-shadow: 0 10px 30px rgba(0,0,0,.35); z-index: 40; display:none; }
        .iqomah-banner .counter h1 { text-align:left; font-size:1.25rem; margin-bottom:.5rem; }
        .iqomah-banner .counter .hh, .iqomah-banner .counter .ii, .iqomah-banner .counter .ss { display:inline-block; font-size:2rem; margin:0 .5rem; }
        .fixed-card { position: fixed; top: 1.25rem; right: 1.25rem; z-index: 30; }
        .card { background: rgba(15,23,42,.8); border-radius: 1rem; padding: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,.25); border: 1px solid rgba(255,255,255,.06); }
        .card .card-title { font-weight: 700; font-size: 1rem; margin-bottom: .75rem; }
        .card .row { display:flex; justify-content:space-between; padding:.25rem 0; font-size: .95rem; }
        .fixed-announcements { position: fixed; right: 1.25rem; top: 10.5rem; z-index: 29; max-width: 380px; }
        .announcement { background: rgba(15,23,42,.75); border-radius: .9rem; padding: .75rem .9rem; box-shadow: 0 10px 30px rgba(0,0,0,.25); border: 1px solid rgba(255,255,255,.06); }
        .announcement .title { font-weight: 800; font-size: .95rem; color: #fde047; }
        .announcement .body { margin-top: .25rem; font-size: .9rem; line-height: 1.4; }
        .glass-panel { background: rgba(15,23,42,.65); border-radius: 1.25rem; box-shadow: 0 25px 60px rgba(0,0,0,.35); border: 1px solid rgba(255,255,255,.08); overflow:hidden; }
        .panel-header { display:flex; align-items:center; gap:.75rem; padding: 1rem 1.25rem; font-weight:700; font-size:1.25rem; border-bottom: 1px solid rgba(255,255,255,.08); }
        .panel-header .icon { width: 30px; height: 30px; display:flex; align-items:center; justify-content:center; border-radius:.6rem; background: rgba(34,197,94,.15); color:#22c55e; }
        .kajian-list { padding: 1rem; }
        .kajian-item { position:relative; border-radius: 1rem; padding: .9rem 1rem; margin: .5rem 0; background: rgba(15,23,42,.55); border: 1px solid rgba(255,255,255,.06); }
        .kajian-item .day { font-size:.8rem; font-weight:800; letter-spacing:.06em; color: #fde047; }
        .kajian-item .title { margin-top:.25rem; font-size:1.25rem; font-weight:800; }
        .kajian-item .speaker { margin-top:.25rem; font-size:.95rem; opacity:.85; display:flex; align-items:center; gap:.5rem; }
        .kajian-item .badge { position:absolute; right:.75rem; top:.75rem; background: rgba(15,23,42,.7); border: 1px solid rgba(255,255,255,.08); padding: .35rem .6rem; border-radius:.5rem; font-size:.8rem; }
        .jumat-panel { padding: 1.25rem; }
        .role-card { border-radius: .9rem; padding: .9rem 1rem; border: 1px solid rgba(255,255,255,.08); margin-top: .9rem; background: rgba(15,23,42,.5); }
        .role-card.primary { background: linear-gradient(180deg, rgba(22,163,74,.35), rgba(22,163,74,.22)); }
        .role-title { text-align:center; font-size:.8rem; font-weight:700; letter-spacing:.08em; opacity:.85; }
        .role-name { margin-top:.35rem; text-align:center; font-size:1.35rem; font-weight:800; }
        .role-grid { display:grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem; }
        .top-card { background: rgba(15,23,42,.65); border-radius: 1rem; box-shadow: 0 25px 60px rgba(0,0,0,.35); border: 1px solid rgba(255,255,255,.08); overflow: hidden; }
        .top-card .content { padding: 1rem; }
        .right-panel .top-card { max-width: 600px; margin-left:0; margin-right:auto; }
        @media (max-width: 768px) { .right-panel .top-card { max-width: 100%; } }
        .azan-content { text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; }
        .azan-title { font-size:3.5rem; font-weight:900; letter-spacing:.08em; }
        .azan-sub { margin-top:.75rem; font-size:1.125rem; opacity:.9; }
        .sholat-content { display:flex; flex-direction:column; align-items:center; justify-content:center; }
        .sholat-title { font-size:4rem; font-weight:900; letter-spacing:.08em; }
        .sholat-sub { margin-top:.75rem; font-size:1.25rem; opacity:.9; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen" x-data="displayApp()" :style="{ '--bgUrl': currentWallpaperUrl, '--primary-color': '{{ optional($display)->primary_color ?? '#6366f1' }}' }">
    <div class="bg-slideshow" :style="{ backgroundImage: currentWallpaperUrl ? 'url(' + currentWallpaperUrl + ')' : '' }"></div>
    <div class="display-overlay"></div>
    @if(optional($display)->show_finance_card)
    <div class="fixed-card">
        <div class="card">
            <div class="card-title">Saldo Kas Mesjid</div>
            <div class="space-y-1">
                <div class="row"><div>Infaq Jumat</div><div x-text="formatRupiah(finances.infaqJumat)"></div></div>
                <div class="row"><div>Infaq</div><div x-text="formatRupiah(finances.infaqLangsung)"></div></div>
                <div class="row"><div>Saldo Kas</div><div x-text="formatRupiah(finances.saldoKas)"></div></div>
                <div class="row"><div>Saldo Bank</div><div x-text="formatRupiah(finances.saldoBank)"></div></div>
                <div class="row" style="font-weight:700"><div>Saldo</div><div x-text="formatRupiah(finances.infaqJumat + finances.infaqLangsung + finances.saldoKas + finances.saldoBank)"></div></div>
            </div>
        </div>
    </div>
    @endif
    <template x-if="quotes.length">
        <div class="fixed-announcements" style="top: 17.5rem">
            <div class="announcement" x-transition :key="idxQuote">
                <div class="title" x-text="quotes[idxQuote].t1"></div>
                <div class="body" x-text="quotes[idxQuote].t2"></div>
            </div>
        </div>
    </template>
    <div id="count-down" class="full-screen" style="display:none">
        <div class="counter">
            <h1>COUNTER</h1>
            <div class="hh">00<span>JAM</span></div>
            <div class="ii">00<span>MENIT</span></div>
            <div class="ss">00<span>DETIK</span></div>
        </div>
    </div>
    <div id="display-adzan" class="full-screen" style="display:none">
        <div class="azan-content">
            <div class="azan-title">ADZAN</div>
            <div class="azan-sub"></div>
        </div>
    </div>
    <div id="display-shalat" class="full-screen" style="display:none">
        <div class="azan-content sholat-content">
            <div class="sholat-title">SHOLAT BERLANGSUNG</div>
            <div class="sholat-sub">Mohon tenang dan matikan HP</div>
        </div>
    </div>
    <div id="iqomah-banner" class="iqomah-banner">
        <div class="counter">
            <h1>IQOMAH</h1>
            <div class="hh">00<span>JAM</span></div>
            <div class="ii">00<span>MENIT</span></div>
            <div class="ss">00<span>DETIK</span></div>
        </div>
    </div>
    <audio id="adzan-audio" src="{{ asset('sound/mecca_56_22.mp3') }}" preload="auto"></audio>
    <div class="p-6 layout">
        <div class="left-panel space-y-4" style="position:relative; z-index: 10;">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xl font-semibold">{{ $mosque->name }}</div>
                    <div class="text-sm opacity-70">{{ $mosque->address }}</div>
                </div>
            </div>
            <div class="clock" x-html="timeText"></div>
            <div class="mt-2 text-2xl font-semibold" x-text="dateText"></div>
            <div id="jadwal" class="space-y-2">
                <div class="schedule-row" :class="{'active': activeKey==='fajr'}"><div>Subuh</div><div>{{ optional($prayer)->fajr_time?->format('H:i') ?? '-' }}</div></div>
                <div class="schedule-row" :class="{'active': activeKey==='dhuhr'}"><div>Dzuhur</div><div>{{ optional($prayer)->dhuhr_time?->format('H:i') ?? '-' }}</div></div>
                <div class="schedule-row" :class="{'active': activeKey==='asr'}"><div>Ashar</div><div>{{ optional($prayer)->asr_time?->format('H:i') ?? '-' }}</div></div>
                <div class="schedule-row" :class="{'active': activeKey==='maghrib'}"><div>Maghrib</div><div>{{ optional($prayer)->maghrib_time?->format('H:i') ?? '-' }}</div></div>
                <div class="schedule-row" :class="{'active': activeKey==='isha'}"><div>Isya</div><div>{{ optional($prayer)->isha_time?->format('H:i') ?? '-' }}</div></div>
            </div>
        </div>
        <div class="right-panel space-y-6" style="position:relative; z-index: 10;">
            @if(optional($display)->show_friday_officer_card)
            <div class="top-card">
                <div class="panel-header"><div class="icon"><i class="fa fa-key"></i></div><div>Petugas Jumat</div></div>
                @if($nextFridayOfficer)
                    <div class="content">
                        <div class="role-title">TANGGAL</div>
                        <div class="role-name">{{ optional($nextFridayOfficer->date)?->format('d M Y') }}</div>
                        <div class="role-card primary" style="margin-top:.75rem;">
                            <div class="role-title">KHATIB</div>
                            <div class="role-name">{{ $nextFridayOfficer->khatib ?? '-' }}</div>
                        </div>
                        <div class="role-grid" style="margin-top:.75rem; gap:.75rem;">
                            <div class="role-card">
                                <div class="role-title">IMAM</div>
                                <div class="role-name" style="font-size:1rem;">{{ $nextFridayOfficer->imam ?? '-' }}</div>
                            </div>
                            <div class="role-card">
                                <div class="role-title">MUADZIN</div>
                                <div class="role-name" style="font-size:1rem;">{{ $nextFridayOfficer->muadzin ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="content text-sm opacity-80">Belum ada data petugas Jumat berikutnya.</div>
                @endif
            </div>
            @endif

            @if(optional($display)->show_kajian_card)
            <div class="top-card">
                <div class="panel-header"><div class="icon"><i class="fa fa-calendar"></i></div><div>Jadwal Kajian</div></div>
                @if($upcomingKajians->count())
                    <div class="content">
                        <div class="kajian-list" style="padding:0; max-height:none;">
                            @php($days = ['Sunday'=>'MINGGU','Monday'=>'SENIN','Tuesday'=>'SELASA','Wednesday'=>'RABU','Thursday'=>'KAMIS','Friday'=>'JUMAT','Saturday'=>'SABTU'])
                            @foreach($upcomingKajians as $k)
                                @php($dayKey = optional($k->starts_at)?->format('l'))
                                <div class="kajian-item" style="margin:.4rem 0;">
                                    <div class="day">{{ $days[$dayKey] ?? strtoupper($dayKey ?? '') }}</div>
                                    <div class="title">{{ $k->title }}</div>
                                    <div class="speaker"><i class="fa fa-user-o"></i><span>{{ $k->speaker ?? '-' }}</span></div>
                                    <div class="badge">{{ $k->notes ? $k->notes : optional($k->starts_at)?->format('H:i') }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="content text-sm opacity-80">Belum ada jadwal kajian.</div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <div class="fixed bottom-4 right-4 bg-slate-900/70 rounded-full p-2">
        @if(optional($mosque->displaySetting)->logo_path)
            <img src="{{ asset('storage/' . $mosque->displaySetting->logo_path) }}" alt="logo" class="h-14 w-14 object-contain">
        @endif
    </div>

    <div class="fixed inset-x-0 bottom-0">
        <div class="mx-6 mb-4 rounded-xl overflow-hidden" style="background: rgba(15,23,42,.9)">
            @if($mosque->runningTexts->count())
                <div class="flex items-center">
                    <div class="px-3 py-2 text-yellow-400">
                        <i class="fa fa-sun-o"></i>
                    </div>
                    <div class="py-2 overflow-hidden w-full">
                        <div class="whitespace-nowrap animate-marquee">
                            @foreach($mosque->runningTexts as $rt)
                                <span class="mr-10 text-sky-200">{{ $rt->content }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="px-4 py-2 text-slate-300">Tidak ada running text.</div>
            @endif
        </div>
    </div>
</body>
</html>
