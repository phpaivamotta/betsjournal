<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Edit Bet') }}
        </h2>
    </x-slot>

    <x-auth-card>
        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

        <form method="POST" action="{{ route('bets.update', ['bet' => $bet]) }}">
            @method('PATCH')
            @csrf

            <!-- match -->
            <div>
                <x-input-label for="match" :value="__('Match')" />

                <x-text-input id="match" class="block mt-1 w-full" type="text" name="match" :value="old('match', $bet->match)"
                    required autofocus />
            </div>

            <!-- result -->
            <div class="flex items-center mt-4">
                <input class="mr-1" type="radio" name="result" id="win"
                    {{ old('result', $bet->result) === 1 ? 'checked' : '' }} value=1>
                <x-input-label for="win" :value="__('Win')" />

                <input class="ml-4 mr-1" type="radio" name="result" id="loss"
                    {{ old('result', $bet->result) === 0 ? 'checked' : '' }} value=0>
                <x-input-label for="loss" :value="__('Loss')" />

                <input class="ml-4 mr-1" type="radio" name="result" id="na"
                    {{ old('result', $bet->result) === null ? 'checked' : '' }} value=''>
                <x-input-label for="na" :value="__('N/A')" />
            </div>

            <!-- bookie -->
            <div class="mt-4">
                <x-input-label for="bookie" :value="__('Bookie')" />

                <x-text-input id="bookie" class="block mt-1 w-full" type="text" name="bookie" :value="old('bookie', $bet->bookie)"
                   required />
            </div>

            <!-- bet size -->
            <div class="mt-4">
                <x-input-label for="bet_size" :value="__('Stake')" />

                <x-text-input id="bet_size" class="block mt-1 w-full" type="number" step="0.01" name="bet_size"
                    :value="old('bet_size', $bet->bet_size)" required />
            </div>

            <!-- odd -->
            <div class="mt-4">
                <x-input-label for="odd" :value="__('Odd')" />

                <x-text-input id="odd" placeholder="{{ auth()->user()->odd_type }}" class="block mt-1 w-full"
                    type="number" step="0.001" name="odd" :value="old(
                        'odd',
                        auth()->user()->odd_type === 'american' ? $bet->american_odd : $bet->decimal_odd,
                    )" required />
            </div>

            <!-- bet type -->
            <div class="mt-4">
                <x-input-label for="bet_type" :value="__('Bet Type')" />

                <x-text-input id="bet_type" class="block mt-1 w-full" type="text" name="bet_type" :value="old('bet_type', $bet->bet_type)"
                   required />
            </div>

            <!-- bet pick -->
            <div class="mt-4">
                <x-input-label for="bet_pick" :value="__('Bet Pick')" />

                <x-text-input id="bet_pick" class="block mt-1 w-full" type="text" name="bet_pick" :value="old('bet_pick', $bet->bet_pick)"
                   required />
            </div>

            <!-- sport -->
            <div class="mt-4">
                <x-input-label for="sport" :value="__('Sport')" />

                <x-text-input id="sport" class="block mt-1 w-full" type="text" name="sport" :value="old('sport', $bet->sport)"
                   required />
            </div>

            {{-- date --}}
            <div class="mt-4">
                <x-input-label for="match_date" :value="__('Match Date')" />

                <input type="date" id="match_date"
                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    name="match_date" value="{{ old('match_date', $bet->match_date) }}"required />
            </div>

            {{-- time --}}
            <div class="mt-4">
                <x-input-label for="match_time" :value="__('Match Time')" />

                <input type="time" id="match_time"
                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    name="match_time" value="{{ old('match_time', substr($bet->match_time, 0, -3)) }}" required />
            </div>

            <!-- description -->
            <div class="mt-4">
                <x-input-label for="bet_description" :value="__('Description (optional)')" />

                <x-text-input id="bet_description" class="block mt-1 w-full" type="text" name="bet_description"
                    :value="old('bet_description', $bet->bet_description)" />
            </div>

            <div class="flex justify-end mt-4">
                <x-primary-button>
                    {{ __('Edit') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-app-layout>
