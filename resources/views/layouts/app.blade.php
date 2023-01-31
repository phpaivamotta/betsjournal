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

    <title>{{ $title ?? 'Betsjournal' }}</title>

    <link rel="icon" href="/img/betsjournal-email-logo.png">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">


    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="antialiased">
    <div class="flex flex-col min-h-screen {{ request()->routeIs('about') ? 'bg-white' : 'bg-gray-100' }}">
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
        <footer class="relative mt-auto h-96 bg-blue-900">
            <div class="absolute bottom-1 w-full text-center">

                <x-journal-icon class="w-10 mx-auto mb-12" />

                <form method="POST" action="/subscribers">
                    @csrf

                    <label for="subscriber-email" class="block text-white text-md font-medium mb-6 px-2">
                        Subscribe to receive free value bets in your inbox.
                    </label>

                    <div class="flex items-center justify-center mb-4 mx-auto h-8">
                        <input type="email" name="subscriber-email" id="subscriber-email"
                            placeholder="Your email address"
                            class="placeholder:text-gray-400 border-none focus:border-indigo-300 focus:ring focus:ring-indigo-400 focus:ring-opacity-50 w-56 sm:w-72 rounded-md" required
                            value="{{ old('subscriber-email') }}">

                        <button type="submit" class="ml-2 bg-blue-500 text-white rounded-md p-2">
                            Subscribe
                        </button>
                    </div>
                </form>

                @error('subscriber-email')
                    <p class="block mt-1 mb-2 text-red-500 text-sm">
                        {{ $message }}
                    </p>
                @enderror

                <p class="text-xs text-gray-300 mb-10">
                    No spam. Just free value bets. Unsubscribe at any time.
                </p>

                <p class="text-[10px] text-white">Data powered by Oddspedia</p>

                <a href="https://oddspedia.com" target="_blank">
                    <img class="mx-auto mt-1" src="{{ asset('img/logo-oddspedia.svg') }}" alt="Oddspedia Logo">
                </a>

                <p class="mt-3 mb-2 text-xs text-white">
                    © {{ now()->year }} <strong>Bets</strong>journal. All rights reserved.
                </p>
            </div>
        </footer>

        <x-flash-subscriber />
    </div>

    @stack('scripts')
    @livewireScripts
</body>

</html>
