<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Value Bets') }}
        </h2>
    </x-slot>

    <x-auth-card class="sm:max-w-lg">

        <p class="text-xl font-semibold">Value Bets</p>

        <p class="font-medium text-sm text-gray-700">
            Find value bets.
        </p>

        <form action="/value-bets" method="POST">
            @csrf

            <!-- Odd Format -->
            <p class="mt-5 mb-1 font-medium text-sm">
                Odd Format:
            </p>

            <div class="flex items-center">

                <input class="mr-1" type="radio" name="odd_type" id="american" value="american" required>
                <x-input-label for="american" :value="__('American')" />

                <input class="ml-4 mr-1" type="radio" name="odd_type" id="decimal" value="decimal">
                <x-input-label for="decimal" :value="__('Decimal')" />

            </div>

            {{-- oddtype error --}}
            <span class="block mt-1 text-red-500 text-xs" id="odd_type_error" style="display: none;"></span>

            {{--  --}}
            {{-- in-season sports --}}
            <div class="mt-5 mb-1 font-medium text-sm">
                <label for="sports">In-season sports:</label>

                <div>
                    <select class="rounded-sm block font-medium text-sm text-gray-700" name="sport" id="sport">
                        @forelse ($sports as $sport)
                            <option value="{{ $sport['key'] }}">{{ ucwords(str_replace('_', ' ', $sport['key'])) }}
                            </option>
                        @empty
                            <option>There are no in-season sports right now.</option>
                        @endforelse
                    </select>
                </div>
            </div>

            {{-- regions --}}
            <div class="mt-5 mb-1 font-medium text-sm">
                <label for="sports">Regions:</label>

                <div class="flex items-center space-x-2">
                    <x-checkbox name="us" />
                    <x-checkbox name="uk" />
                    <x-checkbox name="eu" />
                    <x-checkbox name="au" />
                </div>
            </div>
            {{--  --}}

            <x-primary-button class="mt-4" id="btn">Calculate</x-primary-button>

        </form>

        {{-- betting tool info --}}
        <div class="flex items-center space-x-2 text-lg border-gray-200 border-t-2 mt-6 pt-4">
            <x-info-svg />

            <p>Tool Info</p>
        </div>

        <p class="mt-3 text-gray-700 text-sm">
            Being able to calculate the returns for any single bet is an essential part of betting.
            This might be one of the simplest calculations done in betting, but it is nonetheless essential. Our <span
                class="italic">Payout Calculator</span> makes this calculation even simpler!
        </p>

        <p class="text-gray-700 text-sm mt-3">
            Every bet can essentially be broken down into three elements: the <strong>stake</strong>, the
            <strong>payout</strong>, and the <strong>profit</strong>.
            The stake is the amount being wagered on the bet, this is the amount you risk losing whenever you place a
            bet. On the other hand, the payout is the amount you can win. This consistis of the stake plus the
            <strong>profit</strong>, which is how much money the bookies are giving you for winning the bet.
        </p>

        <p class="text-gray-700 text-sm mt-3">
            For instance, lets suppose Barcelona and Real Madrid are competing in the Champions League Final. A bookie
            has the odds of Real Madrid winning the match at 2.3, in decimal format. This means that, if you stake a 100
            dollars on Real Madrid to win the match, then your potential payout is of 230 dollars and your potential
            profit is of 130 dollars.
        </p>

        {{-- value bets --}}
        @if (isset($matches))
            @forelse ($matches as $match)
                @foreach ($match['valueBets'] as $bookie => $valueBet)
                    <div class="my-4">
                        <p>{{ $match['home'] }}</p>
                        <p>{{ $match['away'] }}</p>
                        <p>{{ $match['sport'] }}</p>
                        <p>{{ $match['dateTime'] }}</p>
                        <p>{{ $bookie }}</p>
                        
                        @if (isset($valueBet['awayOdds']))
                            <p>Home: {{ $valueBet['awayOdds']['moneyline'] }}</p>
                            <p>{{ $valueBet['awayOdds']['overvalue'] }}</p>
                        @endif

                        @if (isset($valueBet['homeOdds']))
                            <p>Away: {{ $valueBet['homeOdds']['moneyline'] }}</p>
                            <p>{{ $valueBet['homeOdds']['overvalue'] }}</p>
                        @endif

                        @if (isset($valueBet['drawOdds']))
                            <p>Draw: {{ $valueBet['drawOdds']['moneyline'] }}</p>
                            <p>{{ $valueBet['drawOdds']['overvalue'] }}</p>
                        @endif
                    </div>
                @endforeach
            @empty
                <p>No value bets found for this sport/region.</p>
            @endforelse
        @endif

    </x-auth-card>
</x-app-layout>
