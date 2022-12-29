<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Bets / New') }}
        </h2>
    </x-slot>

    <x-auth-card class="mb-4">

        {{-- bet links --}}
        <div class="mb-10 mt-1">
            <div class="flex items-center mb-4">

                {{-- new bet link --}}
                <a href="{{ route('bets.index') }}"
                    class="bg-blue-900 font-semibold hover:opacity-75 py-2 rounded-lg text-center text-white w-1/2">
                    <p class="text-sm">
                        All
                    </p>
                </a>

                {{-- stats link --}}
                <a href="{{ route('bets.stats') }}"
                    class="ml-4 bg-blue-900 font-semibold hover:opacity-75 py-2 rounded-lg text-center text-white w-1/2">
                    <p class="text-sm">
                        Stats
                    </p>
                </a>
            </div>
        </div>

        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

        <form method="POST" action="{{ route('bets.store') }}">
            @csrf

            <!-- categories -->
            @if (auth()->user()->categories->count())
                <div>
                    <x-input-label for="categories" :value="__('Categories')" />

                    <select multiple name="categories[]" id="categories"
                        class="text-gray-600 block border-gray-300 h-[42px] mt-1 rounded-md w-full focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @foreach (auth()->user()->categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- match -->
            <div class="mt-4">
                <x-input-label for="match" :value="__('Match')" />

                <x-text-input id="match" class="block mt-1 w-full" type="text" name="match" :value="old('match')"
                    required autofocus />
            </div>

            <!-- result -->
            <div class="flex items-center mt-4">
                <input class="mr-1" type="radio" name="result" id="win" value=1>
                <x-input-label for="win" :value="__('Win')" />

                <input class="ml-4 mr-1" type="radio" name="result" id="loss" value=0>
                <x-input-label for="loss" :value="__('Loss')" />

                <input class="ml-4 mr-1" type="radio" name="result" id="na" value=''>
                <x-input-label for="na" :value="__('N/A')" />
            </div>

            <!-- bookie -->
            <div class="mt-4">
                <x-input-label for="bookie" :value="__('Bookie')" />

                <x-text-input id="bookie" class="block mt-1 w-full" type="text" name="bookie" :value="old('bookie')"
                    required autofocus />
            </div>

            <!-- bet size -->
            <div class="mt-4">
                <x-input-label for="bet_size" :value="__('Stake')" />

                <x-text-input id="bet_size" class="block mt-1 w-full" type="number" step="0.01" name="bet_size"
                    :value="old('bet_size')" required autofocus />
            </div>

            <!-- odd -->
            <div class="mt-4">
                <div class="flex items-center gap-2">
                    <x-input-label for="odd" :value="__('Odd')" />

                    <x-tooltip id="oddTooltip" />
                </div>

                <x-text-input id="odd" placeholder="{{ auth()->user()->odd_type }}" class="block mt-1 w-full"
                    type="number" step="0.001" name="odd" :value="old('odd')" required autofocus />
            </div>

            <!-- bet type -->
            <div class="mt-4">
                <div class="flex items-center gap-2">
                    <x-input-label for="bet_type" :value="__('Bet Type')" />

                    <x-tooltip id="betTypeTooltip" />
                </div>

                <x-text-input id="bet_type" class="block mt-1 w-full" type="text" name="bet_type" :value="old('bet_type')"
                    required autofocus />
            </div>

            <!-- bet pick -->
            <div class="mt-4">
                <x-input-label for="bet_pick" :value="__('Bet Pick')" />

                <x-text-input id="bet_pick" class="block mt-1 w-full" type="text" name="bet_pick" :value="old('bet_pick')"
                    required autofocus />
            </div>

            <!-- sport -->
            <div class="mt-4">
                <x-input-label for="sport" :value="__('Sport')" />

                <x-text-input id="sport" class="block mt-1 w-full" type="text" name="sport" :value="old('sport')"
                    required autofocus />
            </div>

            {{-- date --}}
            <div class="mt-4">
                <x-input-label for="match_date" :value="__('Match Date')" />

                <input type="date" id="match_date"
                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    name="match_date" value="{{ old('match_date') }}" required />
            </div>

            {{-- time --}}
            <div class="mt-4">
                <x-input-label for="match_time" :value="__('Match Time')" />

                <input type="time" id="match_time"
                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    name="match_time" value="{{ old('match_time') }}" required />
            </div>

            <!-- description -->
            <div class="mt-4">
                <div class="flex items-center gap-2">
                    <x-input-label for="bet_description" :value="__('Description (optional)')" />

                    <x-tooltip id="descriptionTooltip" />
                </div>

                <x-text-input id="bet_description" class="block mt-1 w-full" type="text" name="bet_description"
                    :value="old('bet_description')" autofocus />
            </div>

            <div class="flex justify-end mt-4">
                <x-primary-button>
                    {{ __('Create') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>

    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script>
        tippy('#oddTooltip', {
            content: 'Change odds type in Edit Profile',
            trigger: 'mouseenter click',
        });

        tippy('#betTypeTooltip', {
            content: 'e.g., Single, Double, Treble',
            trigger: 'mouseenter click',
        });

        tippy('#descriptionTooltip', {
            content: 'Record any notes about this bet',
            trigger: 'mouseenter click',
        });
    </script>
</x-app-layout>
