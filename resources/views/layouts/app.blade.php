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
            @media (max-width: 768px) {
                #sidenav-main { position: fixed; left: -18rem; top: 0; height: 100vh; transition: left .2s ease; }
                body.sidenav-open #sidenav-main { left: .75rem; }
                #sidenav-overlay { display: none; }
                body.sidenav-open #sidenav-overlay { display: block; }
            }
        </style>
    </head>
    <body class="g-sidenav-show bg-gray-100 font-sans antialiased">
        <div class="min-h-screen flex">
            @auth
                @include('layouts.navigation')
            @endauth

            <button id="mobileNavToggle" class="md:hidden fixed top-3 left-3 z-50 px-3 py-2 bg-white rounded-lg shadow border">Menu</button>
            <div id="sidenav-overlay" class="fixed inset-0 bg-black/40 md:hidden z-40"></div>

            <main class="main-content position-relative border-radius-lg flex-1">
                @isset($header)
                    <header class="bg-white border-b">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset
                <div class="p-4">
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
                if (btn) btn.addEventListener('click', toggle);
                if (overlay) overlay.addEventListener('click', toggle);
            })();
        </script>
    </body>
</html>
