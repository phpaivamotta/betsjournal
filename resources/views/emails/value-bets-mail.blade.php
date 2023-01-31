<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email</title>
</head>

<body class="bg-gray-100 items-center justify-center pt-10">
    <div class="my-10 text-center">
        <a href="{{ route('home') }}" target="_blank">
            <img src="{{ asset('/img/betsjournal-email-logo.png') }}" class="w-8 h-8 inline-block"
                alt="Betsjournal Logo">
        </a>

        <h2 class="text-2xl font-bold my-4">Value Bets</h2>

        <p class="text-sm text-gray-500 font-semibold mb-4">
            Check out some fresh value bet opportunities!
        </p>

        <p class="text-sm text-gray-500 font-semibold">
            Don't want to receive our emails anymore?
            <a href="{{ Illuminate\Support\Facades\URL::signedRoute('subscribers.destroy', ['subscriber' => $subscriberId]) }}"
                class="text-blue-300 hover:underline"
                target="_blank">
                Click here to unsubscribe.
            </a>
        </p>
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
