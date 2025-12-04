<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 shadow"
    id="sidenav-main" style="width: 17rem; z-index: 1050;">
    @php($user = auth()->user())
    @php($dashRouteName = $user?->role === 'super_admin' ? 'super.dashboard' : 'dashboard')
    <div class="sidenav-header">
        <a class="navbar-brand m-0" href="{{ route($dashRouteName) }}">
            <x-application-logo class="navbar-brand-img h-100 w-auto fill-current text-gray-800" />
            <span class="ms-1 font-weight-bold">Admin</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="navbar-collapse w-auto show" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs($dashRouteName) ? 'active' : '' }}" href="{{ route($dashRouteName) }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            @if($user?->role === 'super_admin')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('mosques.*') ? 'active' : '' }}" href="{{ route('mosques.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-building text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Masjid</span>
                </a>
            </li>
            @endif
            @if($user?->role === 'mosque_admin' || $user?->mosque_id)
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-settings-gear-65 text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Pengaturan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}" href="{{ route('announcements.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-notification-70 text-info text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Pengumuman</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('running-texts.*') ? 'active' : '' }}" href="{{ route('running-texts.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-bullet-list-67 text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Running Text</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('friday-officers.*') ? 'active' : '' }}" href="{{ route('friday-officers.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Petugas Jum'at</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kajians.*') ? 'active' : '' }}" href="{{ route('kajians.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Jadwal Kajian</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('mosque.profile') ? 'active' : '' }}" href="{{ route('mosque.profile') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Profil Masjid</span>
                </a>
            </li>
            @if($user?->mosque?->slug)
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/m/' . Auth::user()->mosque->slug) }}" target="_blank">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-image text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Lihat Display</span>
                </a>
            </li>
            @endif
            @endif
            @if($user?->role !== 'super_admin' && !$user?->mosque_id)
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('public.mosques.*') ? 'active' : '' }}" href="{{ route('public.mosques.create') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-building text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Registrasi Masjid</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
    <div class="sidenav-footer mx-3">
        @auth
        <div class="card card-plain shadow-none" id="sidenavCard">
            <div class="card-body text-center p-3 w-100 pt-0">
                <div class="docs-info">
                    <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                    <p class="text-xs font-weight-bold mb-0">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
        <a class="btn btn-dark btn-sm w-100 mb-3" href="{{ route('profile.edit') }}">Profile</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm mb-0 w-100">Logout</button>
        </form>
        @endauth
        @guest
        <a class="btn btn-dark btn-sm w-100 mb-3" href="{{ route('login') }}">Masuk</a>
        <a class="btn btn-primary btn-sm mb-0 w-100" href="{{ route('register') }}">Daftar</a>
        @endguest
    </div>
</aside>
