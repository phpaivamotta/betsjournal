<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
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
        <footer class="relative mt-auto h-24 bg-blue-900">
            <div class="absolute bottom-4 w-full text-center">
                <p class="mb-3 text-sm text-white">
                    © {{ now()->year }} <strong>Bets</strong>journal. All rights reserved.
                </p>

                <div class="flex items-center justify-center">
                    <p class="text-lg text-white">Data powered by </p>

                    <a href="https://oddspedia.com" target="_blank">
                        <img class="" src="{{ asset('img/logo-oddspedia.svg') }}" alt="Oddspedia Logo">
                    </a>
                </div>
            </div>
        </footer>


    </div>

    @stack('scripts')
    @livewireScripts
</body>

</html>
