<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script defer src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css')
    <title>Betsjournal</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>

<body>
    {{-- Navbar --}}
    <nav class="sm:flex sm:items-center sm:justify-between text-white bg-blue-900 h-14 w-full">
        {{-- Logo --}}
        <a href="{{ route('home') }}">
            <div class="inline-block">
                <h3 class="ml-3 pt-3.5 sm:pt-0 text-lg">
                    <strong>Bets</strong>journal
                </h3>
            </div>
        </a>

        {{-- Regular screen navigation links --}}
        <div class="hidden sm:block mr-3">
            @auth
                <a href="{{ url('/dashboard') }}" class="text-md">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-md">Log in</a>
                <a href="{{ route('register') }}" class="ml-4 text-md">Register</a>
            @endauth
        </div>

        {{-- Mobile navigation --}}
        <div x-data={show:false} @click.away="show=false" class="block sm:hidden">
            <!-- Hamburger -->
            <button @click="show=!show" class="absolute top-4 right-2 rounded-md text-white">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            {{-- expanding links --}}
            <div x-show="show" class="flex flex-col text-white text-sm mt-3 bg-blue-900" style="display:none">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-xs pb-2 ml-3">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-xs pb-2 ml-3">Log in</a>
                    <a href="{{ route('register') }}" class="text-xs pb-2 ml-3">Register</a>
                @endauth
            </div>
        </div>
    </nav>
</body>

</html>
