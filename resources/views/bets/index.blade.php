<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Bets') }}
        </h2>
    </x-slot>

    {{-- bet card --}}
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 space-y-4">
        @forelse ($bets as $bet)
            <div class="p-4 xborder-2 xborder-blue-500 rounded-lg shadow-lg">
                {{-- match --}}
                <h2 class="text-blue-900 text-xl font-bold">
                    {{ $bet->match }}
                </h2>

                {{-- bet size --}}
                <p>
                    <span class="text-sm font-bold">Size:</span>
                    <span class="text-xs">${{ $bet->bet_size }}</span>
                </p>

                {{-- bet odds --}}
                <p>
                    <span class="text-sm font-bold">Odds:</span>
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
                            <span class="text-sm font-bold">{{ ucwords(str_replace('_', ' ', $optional)) }}</span>
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
