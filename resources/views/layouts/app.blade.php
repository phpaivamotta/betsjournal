<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">


    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

        svg {
            /* border: 1px solid #aaa; */
        }
    </style>

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- tailwind through vite is useless --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    <div class="flex flex-col min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        <header class="sm:hidden bg-blue-900 shadow">
            <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="relative mt-auto h-14 bg-blue-900">
            <div class="absolute bottom-1 w-full text-center">
                <p class="text-xs text-white">
                    © 2022 Betsjournal. All rights reserved.
                </p>
            </div>
        </footer>

    </div>
</body>

</html>
