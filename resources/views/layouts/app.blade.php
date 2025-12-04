<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/argon-dashboard/2.0.4/css/nucleo-icons.min.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/argon-dashboard/2.0.4/css/nucleo-svg.min.css" rel="stylesheet" />
        <link id="pagestyle" href="https://cdnjs.cloudflare.com/ajax/libs/argon-dashboard/2.0.4/css/argon-dashboard.min.css" rel="stylesheet" />
        <style>
            body { overflow-x: hidden; }
            @media (max-width: 768px) {
                #sidenav-main { position: fixed; left: .75rem; top: .75rem; height: 100vh; margin-top: 0; transition: transform .25s ease; transform: translateX(-110%); z-index: 1050; border-radius: .75rem; }
                body.sidenav-open #sidenav-main { transform: translateX(0); }
                #sidenav-overlay { display: none; }
                body.sidenav-open #sidenav-overlay { display: block; }
                .main-content { padding-top: 56px; }
                #mobileNavToggle { top: auto !important; bottom: .75rem; right: .75rem; }
                .mobile-safe-area { padding-bottom: calc(120px + env(safe-area-inset-bottom)); }
            }
            @media (min-width: 768px) {
                .main-content { margin-left: 17rem; }
                #sidenav-main { transform: none !important; }
            }
        </style>
    </head>
    <body class="g-sidenav-show bg-gray-100 font-sans antialiased">
        <div class="min-h-screen flex">
            @auth
                @include('layouts.navigation')
            @endauth

            
            <button id="mobileNavToggle" class="btn btn-light d-md-none position-fixed top-0 end-0 m-2 rounded-circle shadow" aria-label="Toggle navigation" style="z-index: 1060; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <i class="ni ni-align-left-2 text-secondary" style="font-size: 1.1rem;"></i>
            </button>
            <div id="sidenav-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-md-none" style="background-color: rgba(0,0,0,.4); z-index: 1040;"></div>

            <main class="main-content position-relative border-radius-lg flex-1" style="min-height: 100vh;">
                @isset($header)
                    <header class="bg-white border-bottom">
                        <div class="container-fluid py-3 px-3 px-md-4">
                            {{ $header }}
                        </div>
                    </header>
                @endisset
                <div class="p-3 p-md-4">
                    {{ $slot }}
                </div>
            </main>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/argon-dashboard/2.0.4/js/argon-dashboard.min.js"></script>
        <script>
            (function(){
                var btn = document.getElementById('mobileNavToggle');
                var overlay = document.getElementById('sidenav-overlay');
                function toggle(){ document.body.classList.toggle('sidenav-open'); }
                function close(){ document.body.classList.remove('sidenav-open'); }
                if (btn) btn.addEventListener('click', toggle);
                if (overlay) overlay.addEventListener('click', close);
            })();
        </script>
    </body>
</html>
