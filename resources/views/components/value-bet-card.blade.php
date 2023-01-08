@props(['match', 'bookieName', 'valueBetOffering', 'valueBetStats', 'oddFormat'])

<div class="flex items-start justify-between p-4 rounded-lg shadow-md hover:shadow-lg bg-white mb-4">
    <div class="w-full">

        <header class="flex items-start justify-between mb-4 border-b border-gray-400 pb-2">

            <div>
                {{-- match --}}
                <h2 class="text-blue-900 text-lg font-bold">
                    {{ $match['home'] . ' vs ' . $match['away'] }}
                </h2>

                <div class="flex items center">
                    {{-- dateTime --}}
                    <p class="text-xs">
                        {{ \Carbon\Carbon::create($match['dateTime'])->toDayDateTimeString() }}
                    </p>
                </div>

            </div>
        </header>

        <main>
            <div class="flex items-center justify-between">

                <div>
                    {{-- away --}}
                    @if ($valueBetOffering === 'awayOdds')
                        {{-- set bet pick --}}
                        @php
                            $betPick = $match['away'];
                        @endphp

                        {{-- bet pick --}}
                        <p>
                            <span class="text-md font-bold">{{ $match['away'] }}</span>
                        </p>

                        {{-- market type --}}
                        <p class="text-xs">
                            Money Line
                        </p>

                        {{-- get the moneyline odd and overvalue here --}}
                        @php
                            $odd = $valueBetStats['moneyline'];
                            // ddd($odd);
                            $overvalue = $valueBetStats['overvalue'];
                            $numBookies = $match['awayAvg']['numBookies'];
                        @endphp
                    @elseif ($valueBetOffering === 'homeOdds')
                        {{-- home --}}

                        {{-- set bet pick --}}
                        @php
                            $betPick = $match['home'];
                        @endphp

                        {{-- bet pick --}}
                        <p>
                            <span class="text-md font-bold">{{ $match['home'] }}</span>
                        </p>

                        {{-- market type --}}
                        <p class="text-xs">
                            Money Line
                        </p>

                        {{-- get the moneyline odd and overvalue % here --}}
                        @php
                            $odd = $valueBetStats['moneyline'];
                            $overvalue = $valueBetStats['overvalue'];
                            $numBookies = $match['awayAvg']['numBookies'];
                        @endphp
                    @elseif ($valueBetOffering === 'drawOdds')
                        {{-- draw --}}

                        {{-- set bet pick --}}
                        @php
                            $betPick = 'draw';
                        @endphp

                        {{-- bet pick --}}
                        <p>
                            <span class="text-md font-bold">Draw</span>
                        </p>

                        {{-- market type --}}
                        <p class="text-xs">
                            Money Line
                        </p>

                        {{-- get the moneyline odd and overvalue % here --}}
                        @php
                            $odd = $valueBetStats['moneyline'];
                            $overvalue = $valueBetStats['overvalue'];
                            $numBookies = $match['awayAvg']['numBookies'];
                        @endphp
                    @endif
                </div>

                <div>
                    {{-- bet odd --}}
                    <div class="flex items-center gap-2">

                        <p class="font-semibold">
                            @if ($oddFormat === 'american')
                                <span class="text-sm">
                                    @ {{ number_format(\App\Services\ConvertOddsService::decimalToAmerican($odd), 0) }}
                                </span>
                            @elseif ($oddFormat === 'decimal')
                                <span class="text-sm">
                                    @ {{ number_format($odd, 2) }}
                                </span>
                            @endif
                        </p>
                    </div>

                    {{-- implied probability --}}
                    <p class="text-xs text-right">
                        {{ number_format((1 / $odd) * 100, 0) . '%' }}
                    </p>

                </div>

            </div>

            <p class="text-sm mt-4">
                {{ ucwords(str_replace('_', ' ', $bookieName)) }}
            </p>

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
                        <span class="text-md font-semibold">{{ number_format($overvalue * 100, 2) . '%' }}</span>
                    </p>
                </div>

                {{-- #bookies --}}
                <div>
                    <p>
                        <span class="text-xs">#Bookies Analyzed</span>
                    </p>

                    <p>
                        <span class="text-md font-semibold">{{ $numBookies }}</span>
                    </p>
                </div>

            </div>

            <div>
                <a
                    href="{{ route('value-bets.record', [
                        'match' => $match['home'] . ' vs ' . $match['away'],
                        'bookie' => $bookieName,
                        'odd' => number_format($odd, 2),
                        'betPick' => $betPick,
                        'sport' => $match['sport'],
                        'date' => \Carbon\Carbon::create($match['dateTime'])->toDateString(),
                        'time' => \Carbon\Carbon::create($match['dateTime'])->format('H:i'),
                    ]) }}">
                    <x-primary-button type="button">
                        Record
                    </x-primary-button>
                </a>
            </div>

        </footer>

    </div>

</div>
