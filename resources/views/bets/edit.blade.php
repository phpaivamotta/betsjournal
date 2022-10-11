<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Edit Bet') }}
        </h2>
    </x-slot>

    <x-auth-card class="mb-4">
        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

        <form method="POST" action="{{ route('bets.update', ['bet' => $bet]) }}">
            @method('PATCH')
            @csrf

            <!-- match -->
            <div>
                <x-input-label for="match" :value="__('Match*')" />

                <x-text-input id="match" class="block mt-1 w-full" type="text" name="match" :value="old('match', $bet->match)"
                    required autofocus />
            </div>

            <!-- result -->
            <div class="flex items-center mt-4">
                <input class="mr-1" type="radio" name="result" id="win"
                    {{ old('result', $bet->result) == true ? 'checked' : '' }} value=1>
                <x-input-label for="win" :value="__('Win')" />

                <input class="ml-4 mr-1" type="radio" name="result" id="loss"
                    {{ old('result', $bet->result) == false ? 'checked' : '' }} value=0>
                <x-input-label for="loss" :value="__('Loss')" />
            </div>

            <!-- bookie -->
            <div class="mt-4">
                <x-input-label for="bookie" :value="__('Bookie')" />

                <x-text-input id="bookie" class="block mt-1 w-full" type="text" name="bookie" :value="old('bookie', $bet->bookie)"
                    />
            </div>

            <!-- bet size -->
            <div class="mt-4">
                <x-input-label for="bet_size" :value="__('Bet Size*')" />

                <x-text-input id="bet_size" class="block mt-1 w-full" type="number" step="0.01" name="bet_size"
                    :value="old('bet_size', $bet->bet_size)" required />
            </div>

            <!-- odd -->
            <div class="mt-4">
                <x-input-label for="odd" :value="__('Odd*')" />

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
                    />
            </div>

            <!-- bet pick -->
            <div class="mt-4">
                <x-input-label for="bet_pick" :value="__('Bet Pick')" />

                <x-text-input id="bet_pick" class="block mt-1 w-full" type="text" name="bet_pick" :value="old('bet_pick', $bet->bet_pick)"
                    />
            </div>

            <!-- sport -->
            <div class="mt-4">
                <x-input-label for="sport" :value="__('Sport')" />

                <x-text-input id="sport" class="block mt-1 w-full" type="text" name="sport" :value="old('sport', $bet->sport)"
                    />
            </div>

            {{-- date --}}
            <div class="mt-4">
                <x-input-label for="match_date" :value="__('Match Date')" />

                <input type="date" id="match_date"
                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    name="match_date" value="{{ old('match_date', $bet->match_date) }}" />
            </div>

            {{-- time --}}
            <div class="mt-4">
                <x-input-label for="match_time" :value="__('Match Time')" />

                <input type="time" id="match_time"
                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    name="match_time" value="{{ old('match_time', substr($bet->match_time, 0, -3)) }}" />
            </div>

            <!-- description -->
            <div class="mt-4">
                <x-input-label for="bet_description" :value="__('Description')" />

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
