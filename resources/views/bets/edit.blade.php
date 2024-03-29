<x-app-layout>
    <x-slot:title>
        Edit Bet | Betsjournal
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Edit Bet') }}
        </h2>
    </x-slot>

    <x-auth-card>
        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

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

        <form method="POST" action="{{ route('bets.update', ['bet' => $bet]) }}">
            @method('PATCH')
            @csrf

            <!-- categories -->
            @if (auth()->user()->categories->count())
                <div>
                    <x-input-label for="categories" :value="__('Categories')" />

                    <select multiple name="categories[]" id="categories"
                        class="text-gray-600 block border-gray-300 h-[42px] mt-1 rounded-md w-full focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">

                        @foreach (auth()->user()->categories as $category)
                            <option {{ $bet->categories->contains($category) ? 'selected' : '' }}
                                value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach

                    </select>
                </div>
            @endif

            <!-- match -->
            <div class="mt-4">
                <x-input-label for="match" :value="__('Match')" />

                <x-text-input id="match" class="block mt-1 w-full" type="text" name="match" :value="old('match', $bet->match)"
                    required autofocus />
            </div>

            <div x-data="{ open: {{ $bet->result === 2 ? 1 : 0 }} }">
                <!-- result -->
                <div class="flex items-center mt-4">
                    <input x-on:click="open = false" class="mr-1" type="radio" name="result" id="win"
                        {{ old('result', $bet->result) === 1 ? 'checked' : '' }} value=1>
                    <x-input-label for="win" :value="__('Win')" />

                    <input x-on:click="open = false" class="ml-4 mr-1" type="radio" name="result" id="loss"
                        {{ old('result', $bet->result) === 0 ? 'checked' : '' }} value=0>
                    <x-input-label for="loss" :value="__('Loss')" />

                    <input x-on:click="open = false" class="ml-4 mr-1" type="radio" name="result" id="na"
                        {{ old('result', $bet->result) === null ? 'checked' : '' }} value=''>
                    <x-input-label for="na" :value="__('N/A')" />

                    <input x-on:click="open = true" class="ml-4 mr-1" type="radio" name="result" id="cashout"
                        {{ old('result', $bet->result) === 2 ? 'checked' : '' }} value=2>
                    <x-input-label for="cashout" :value="__('CO')" />
                </div>

                <!-- cashout -->
                <div x-show="open" class="mt-4" style="display: none;">
                    <x-input-label for="cashout" :value="__('Cashout')" />

                    <x-text-input id="cashout" class="block mt-1 w-full" type="number" step="0.01" name="cashout"
                        :value="old('cashout', $bet->cashout)" autofocus />
                </div>
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
                <div class="flex items-center gap-2">
                    <x-input-label for="odd" :value="__('Odd')" />

                    <span id="oddTooltip">
                        <svg class="w-3" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="Page-1" stroke="none" stroke-width="1" fill="#252525" fill-rule="evenodd">
                                <g id="icon-shape">
                                    <path
                                        d="M2.92893219,17.0710678 C6.83417511,20.9763107 13.1658249,20.9763107 17.0710678,17.0710678 C20.9763107,13.1658249 20.9763107,6.83417511 17.0710678,2.92893219 C13.1658249,-0.976310729 6.83417511,-0.976310729 2.92893219,2.92893219 C-0.976310729,6.83417511 -0.976310729,13.1658249 2.92893219,17.0710678 L2.92893219,17.0710678 Z M15.6568542,15.6568542 C18.7810486,12.5326599 18.7810486,7.46734008 15.6568542,4.34314575 C12.5326599,1.21895142 7.46734008,1.21895142 4.34314575,4.34314575 C1.21895142,7.46734008 1.21895142,12.5326599 4.34314575,15.6568542 C7.46734008,18.7810486 12.5326599,18.7810486 15.6568542,15.6568542 Z M9,11 L9,10.5 L9,9 L11,9 L11,15 L9,15 L9,11 Z M9,5 L11,5 L11,7 L9,7 L9,5 Z"
                                        id="Combined-Shape"></path>
                                </g>
                            </g>
                        </svg>
                    </span>
                </div>

                <x-text-input id="odd" placeholder="{{ auth()->user()->odd_type }}" class="block mt-1 w-full"
                    type="number" step="0.001" name="odd" :value="old(
                        'odd',
                        auth()->user()->odd_type === 'american' ? $bet->american_odd : $bet->decimal_odd,
                    )" required />
            </div>

            <!-- bet type -->
            <div class="mt-4">
                <div class="flex items-center gap-2">
                    <x-input-label for="bet_type" :value="__('Bet Type')" />

                    <span id="betTypeTooltip">
                        <svg class="w-3" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="Page-1" stroke="none" stroke-width="1" fill="#252525" fill-rule="evenodd">
                                <g id="icon-shape">
                                    <path
                                        d="M2.92893219,17.0710678 C6.83417511,20.9763107 13.1658249,20.9763107 17.0710678,17.0710678 C20.9763107,13.1658249 20.9763107,6.83417511 17.0710678,2.92893219 C13.1658249,-0.976310729 6.83417511,-0.976310729 2.92893219,2.92893219 C-0.976310729,6.83417511 -0.976310729,13.1658249 2.92893219,17.0710678 L2.92893219,17.0710678 Z M15.6568542,15.6568542 C18.7810486,12.5326599 18.7810486,7.46734008 15.6568542,4.34314575 C12.5326599,1.21895142 7.46734008,1.21895142 4.34314575,4.34314575 C1.21895142,7.46734008 1.21895142,12.5326599 4.34314575,15.6568542 C7.46734008,18.7810486 12.5326599,18.7810486 15.6568542,15.6568542 Z M9,11 L9,10.5 L9,9 L11,9 L11,15 L9,15 L9,11 Z M9,5 L11,5 L11,7 L9,7 L9,5 Z"
                                        id="Combined-Shape"></path>
                                </g>
                            </g>
                        </svg>
                    </span>
                </div>

                <x-text-input id="bet_type" class="block mt-1 w-full" type="text" name="bet_type"
                    :value="old('bet_type', $bet->bet_type)" required />
            </div>

            <!-- bet pick -->
            <div class="mt-4">
                <x-input-label for="bet_pick" :value="__('Bet Pick')" />

                <x-text-input id="bet_pick" class="block mt-1 w-full" type="text" name="bet_pick"
                    :value="old('bet_pick', $bet->bet_pick)" required />
            </div>

            <!-- sport -->
            <div class="mt-4">
                <x-input-label for="sport" :value="__('Sport')" />

                <x-text-input id="sport" class="block mt-1 w-full" type="text" name="sport"
                    :value="old('sport', $bet->sport)" required />
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
                <div class="flex items-center gap-2">
                    <x-input-label for="bet_description" :value="__('Description (optional)')" />

                    <x-tooltip id="descriptionTooltip" />
                </div>

                <x-text-input id="bet_description" class="block mt-1 w-full" type="text" name="bet_description"
                    :value="old('bet_description', $bet->bet_description)" />
            </div>

            {{-- page --}}
            <input type="hidden" name="page" value="{{ request('page') }}">

            <div class="flex justify-end items-center mt-4">
                <a href="{{ route('bets.index', ['page' => request('page')]) }}" class="text-sm text-blue-500 mr-4">
                    Cancel
                </a>

                <x-primary-button>
                    {{ __('Edit') }}
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
