<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-R3Q0SGL1QN"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-R3Q0SGL1QN');
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Betsjournal</title>

    <link rel="icon" href="{{ asset('favicon.svg') }}">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">


    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="antialiased">
    <div class="flex flex-col min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="sm:hidden bg-blue-900 shadow">
                <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="relative mt-auto h-32 bg-blue-900">
            <div class="absolute bottom-1 w-full text-center">
                
                <p class="text-[10px] text-white">Data powered by Oddspedia</p>
                
                <a href="https://oddspedia.com" target="_blank">
                    <img class="mx-auto mt-1" src="{{ asset('img/logo-oddspedia.svg') }}" alt="Oddspedia Logo">
                </a>
                
                <p class="mt-3 text-xs text-white">
                    © {{ now()->year }} <strong>Bets</strong>journal. All rights reserved.
                </p>
            </div>
        </footer>


    </div>

    @stack('scripts')
    @livewireScripts
</body>

</html>
