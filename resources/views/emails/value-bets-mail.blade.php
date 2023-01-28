<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">


    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 items-center justify-center pt-10">

    <div class="my-10 text-center">
        <img src="{{ asset('/img/betsjournal-email-logo.png') }}" class="w-8 h-8 inline-block" alt="Betsjournal Logo">
        <h2 class="text-2xl font-bold my-4">Value Bets</h2>
        <p class="text-sm text-gray-500 font-semibold">Check out some fresh value bet opportunities!</p>
    </div>

    <div class="mb-6 mt-6 mx-auto sm:max-w-md w-11/12">
        @foreach ($matches as $match)
            @foreach ($match['value_bets'] as $outcome => $valueBets)
                @foreach ($valueBets as $bookie => $stats)
                    <x-value-bet-card :match="$match" :outcome="$outcome" odd-format='decimal' :stats="$stats"
                        :bookie="$bookie" />
                @endforeach
            @endforeach
        @endforeach
    </div>

    <div class="w-full text-center my-6">
        <p class="text-xs text-gray-700">
            © {{ now()->year }} <strong>Bets</strong>journal. All rights reserved.
        </p>
    </div>
</body>

</html>