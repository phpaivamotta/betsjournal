<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Bets') }}
        </h2>
    </x-slot>

    <div class="py-12">
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
    </div>
</x-app-layout>