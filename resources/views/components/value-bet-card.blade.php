@props(['match', 'bookie', 'stats', 'oddFormat', 'outcome'])

<div class="flex items-start justify-between p-4 rounded-lg shadow-md hover:shadow-lg bg-white mb-4">
    <div class="w-full">

        <header class="flex items-start justify-between mb-4 border-b border-gray-400 pb-2">

            <div>
                {{-- match --}}
                <h2 class="text-blue-900 text-lg font-bold">
                    {{ $match['home_team'] . ' vs ' . $match['away_team'] }}
                </h2>

                <div class="flex items center">
                    {{-- dateTime --}}
                    <p class="text-xs">
                        {{ \Carbon\Carbon::create($match['commence_time'])->toDayDateTimeString() }}
                    </p>
                </div>

            </div>
        </header>

        <main>
            <div class="flex items-center justify-between">

                <div>
                    {{-- bet pick --}}
                    <p>
                        @if ($outcome === 'draw')
                            <span class="text-md font-bold">Draw</span>
                        @else
                            <span class="text-md font-bold">{{ $match[$outcome] }}</span>
                        @endif
                    </p>

                    {{-- market type --}}
                    <p class="text-xs">
                        Money Line
                    </p>
                </div>

                <div>
                    {{-- bet odd --}}
                    <div class="flex items-center gap-2">

                        <p class="font-semibold">
                            @if ($oddFormat === 'american')
                                <span class="text-sm">
                                    @
                                    {{ number_format(\App\Services\ConvertOddsService::decimalToAmerican($stats['money_line']), 0) }}
                                </span>
                            @elseif ($oddFormat === 'decimal')
                                <span class="text-sm">
                                    @ {{ number_format($stats['money_line'], 2) }}
                                </span>
                            @endif
                        </p>
                    </div>

                    {{-- implied probability --}}
                    <p class="text-xs text-right">
                        {{ number_format((1 / $stats['money_line']) * 100, 0) . '%' }}
                    </p>

                </div>

            </div>

            <a href="{{ App\Services\ValueBetsService::BOOKIE_LINKS[$bookie]['link'] ?? '#' }}" target="_blank"
                class="text-sm text-blue-400 hover:underline mt-4 inline-block">
                {{ App\Services\ValueBetsService::BOOKIE_LINKS[$bookie]['name'] ?? '--'}}
            </a>

            <p class="text-sm">
                {{ ucwords(str_replace('_', ' ', $match['sport'])) }}
            </p>
        </main>

        <footer class="flex items-center justify-between border-t border-gray-400 mt-4 pt-2">

            <div class="flex items-center justify-between w-3/5 lg:w-1/2">

                {{-- bet overvalue --}}
                <div>
                    <p>
                        <span class="text-xs">Overvalue</span>
                    </p>

                    <p>
                        <span
                            class="text-md font-semibold">{{ number_format($stats['over_value'] * 100, 2) . '%' }}</span>
                    </p>
                </div>

                {{-- #bookies --}}
                <div>
                    <p>
                        <span class="text-xs">#Bookies Analyzed</span>
                    </p>

                    <p>
                        <span class="text-md font-semibold">{{ $match['num_bookies_analyzed'][$outcome] }}</span>
                    </p>
                </div>

            </div>

            {{-- record bet --}}
            <div>
                <a
                    href="{{ route('value-bets.record', [
                        'match' => $match['home_team'] . ' vs ' . $match['away_team'],
                        'bookie' => $bookie,
                        'odd' => number_format($stats['money_line'], 2),
                        'betPick' => $outcome === 'draw' ? 'Draw' : $match[$outcome],
                        'sport' => $match['sport'],
                        'date' => \Carbon\Carbon::create($match['commence_time'])->toDateString(),
                        'time' => \Carbon\Carbon::create($match['commence_time'])->format('H:i'),
                    ]) }}">
                    <x-primary-button type="button">
                        Record
                    </x-primary-button>
                </a>
            </div>

        </footer>

    </div>

</div>
