<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Bets') }}
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto py-4 px-4 sm:px-6 lg:px-8 space-y-4 bg-gray-100">

        <div class="flex items-center">
            {{-- new bet link --}}
            <a href="/bets/create"
                class="inline-flex items-center text-white font-semibold rounded-lg bg-blue-900 p-2 hover:opacity-75">
                <p class="text-sm mr-2">
                    New Bet
                </p>

                <p>
                    +
                </p>
            </a>

            {{-- stats link --}}
            <a href="/stats"
                class="ml-4 text-white font-semibold rounded-lg bg-blue-900 px-2 py-2.5 hover:opacity-75">
                <p class="text-sm">
                    Stats
                </p>
            </a>
        </div>

        @forelse ($bets as $bet)
            {{-- bet card --}}
            <div
                class="p-4 rounded-lg shadow-md hover:shadow-lg bg-white">
                {{-- match --}}
                <h2 class="text-blue-900 text-xl mb-4 font-bold">
                    {{ $bet->match }}
                </h2>

                {{-- bet size --}}
                <p>
                    <span class="text-sm font-bold">Size:</span>
                    <span class="text-xs">${{ $bet->bet_size }}</span>
                </p>

                {{-- bet odd --}}
                <p>
                    <span class="text-sm font-bold">Odd:</span>
                    @if (auth()->user()->odd_type === 'american')
                        <span class="text-xs">
                            @if ($bet->american_odd > 0)
                                {{ '+' . $bet->american_odd . ' (' . auth()->user()->odd_type . ')' }}
                            @else
                                {{ $bet->american_odd . ' (' . auth()->user()->odd_type . ')' }}
                            @endif
                        </span>
                    @elseif (auth()->user()->odd_type === 'decimal')
                        <span class="text-xs">{{ $bet->decimal_odd . ' (' . auth()->user()->odd_type . ')' }}</span>
                    @endif
                </p>

                {{-- disply result if there is one --}}
                @if (isset($bet->result))
                    {{-- if win --}}
                    @if ($bet->result)
                        <p>
                            <span class="text-sm font-bold">Result: </span>
                            <span class="text-xs">Win</span>
                        </p>

                        {{-- bet payoff --}}
                        <p>
                            <span class="text-sm font-bold">Payoff:</span>
                            <span class="text-xs">${{ $bet->payoff() }}</span>
                        </p>

                        {{-- if loss --}}
                    @else
                        <p>
                            <span class="text-sm font-bold">Result: </span>
                            <span class="text-xs">Loss</span>
                        </p>

                        {{-- bet payoff --}}
                        <p>
                            <span class="text-sm font-bold">Payoff:</span>
                            <span class="text-xs">$0</span>
                        </p>
                    @endif

                    {{-- if there is no result yet --}}
                @else
                    <p>
                        <span class="text-sm font-bold">Result: </span>
                        <span class="text-xs">N/A</span>
                    </p>

                    {{-- potential bet payoff --}}
                    <p>
                        <span class="text-sm font-bold">Potential Payoff:</span>
                        <span class="text-xs text-gray-500">${{ $bet->payoff() }}</span>
                    </p>
                @endif

                {{-- optional attributes --}}
                @foreach ($optional_attributes as $optional)
                    @if (isset($bet->$optional))
                        <p>
                            <span class="text-sm font-bold">{{ ucwords(str_replace('_', ' ', $optional)) }}: </span>
                            <span class="text-xs">{{ $bet->$optional }}</span>
                        </p>
                    @endif
                @endforeach

            </div>
        @empty
            <p>You haven't logged any bets yet.</p>
        @endforelse
    </div>

</x-app-layout>
