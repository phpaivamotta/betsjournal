<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Bets') }}
        </h2>
    </x-slot>

    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @foreach ($bets as $bet)
                        <h2>{{ $bet->match }}</h2>
                        <p>{{ $bet->bet_size }}</p>
                        <p>{{ $bet->odds }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div> --}}

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

                {{-- bet payoff --}}
                <p>
                    <span class="text-sm font-bold">Payoff:</span>
                    <span class="text-xs">${{ $bet->payoff() }}</span>
                </p>
            </div>
        @empty
            <p>You haven't logged any bets yet.</p>
        @endforelse
    </div>

</x-app-layout>
