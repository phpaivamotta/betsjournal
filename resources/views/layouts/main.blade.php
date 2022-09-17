<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script defer src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Tailwind through vite is fucking useless --}}
    {{-- @vite('resources/css/app.css') --}}
    <title>Betsjournal</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

        svg {
            /* border: 1px solid #aaa; */
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">

    {{-- Navbar --}}
    <nav class="sm:flex sm:items-center sm:justify-between text-white bg-blue-900 h-14 w-full">
        {{-- Logo --}}
        <div class="inline-block h-full">
            <a href="{{ route('home') }}" class="flex items-center h-full">
                {{-- journal icon --}}
                <svg class="ml-3 w-6" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g id="Page-1" stroke="none" stroke-width="1" fill="white" fill-rule="evenodd">
                        <g id="icon-shape">
                            <path
                                d="M2,1.99079514 C2,0.891309342 2.89706013,0 4.00585866,0 L14.9931545,0 C15.5492199,0 16,0.443864822 16,1 L16,2 L5.00247329,2 C4.44882258,2 4,2.44386482 4,3 C4,3.55228475 4.44994876,4 5.00684547,4 L16.9931545,4 C17.5492199,4 18,4.44463086 18,5.00087166 L18,18.0059397 C18,19.1072288 17.1054862,20 16.0059397,20 L3.99406028,20 C2.8927712,20 2,19.1017876 2,18.0092049 L2,1.99079514 Z M6,4 L10,4 L10,12 L8,10 L6,12 L6,4 Z"
                                id="Combined-Shape"></path>
                        </g>
                    </g>
                </svg>

                <div class="xinline-block">
                    <h3 class="ml-1.5 sm:pt-0 text-lg">
                        <strong>Bets</strong>journal
                    </h3>
                </div>
            </a>
        </div>

        {{-- Regular screen navigation links --}}
        <div class="hidden sm:block mr-3">
            @auth
                <a href="{{ url('/dashboard') }}" class="text-sm">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-sm">Log in</a>
                <a href="{{ route('register') }}" class="ml-4 text-sm">Register</a>
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
            <div x-show="show" class="flex flex-col text-white text-sm border-t-[1px] pt-2 bg-blue-900"
                style="display:none">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-xs pb-2 ml-3">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-xs pb-2 ml-3">Log in</a>
                    <a href="{{ route('register') }}" class="text-xs pb-2 ml-3">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    {{ $slot }}

    {{-- Footer --}}
    <footer class="relative mt-auto h-14 bg-blue-900">

        <div class="absolute bottom-1 w-full text-center">
            <p class="text-xs text-white">
                © 2022 Betsjournal. All rights reserved.
            </p>
        </div>

    </footer>

</body>

</html>
