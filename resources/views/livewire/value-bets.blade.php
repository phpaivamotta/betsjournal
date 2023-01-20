<div>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Value Bets') }}
        </h2>
    </x-slot>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <x-find-value-bets-card>

            <p class="text-xl font-semibold">Value Bets</p>

            <p class="font-medium text-sm text-gray-700">
                Find value bets.
            </p>

            <form wire:submit.prevent="getValueBets">

                <!-- Odd Format -->
                <p class="mt-5 block font-medium text-sm text-gray-700">
                    Odd Format
                </p>

                <div class="flex items-center mt-1">

                    <input class="mr-1" wire:model="oddFormat" type="radio" value="american" id="american">
                    <x-input-label for="american" :value="__('American')" />

                    <input class="ml-4 mr-1" wire:model="oddFormat" type="radio" value="decimal" id="decimal">
                    <x-input-label for="decimal" :value="__('Decimal')" />

                </div>

                {{-- odd type error --}}
                @error('oddFormat')
                    <span class="block mt-1 text-red-500 text-xs">{{ $message }}</span>
                @enderror

                {{-- in-season sports --}}
                <div class="mt-5 mb-1 font-medium text-sm">
                    <x-input-label for="sports" :value="__('Sport')" />

                    <div>
                        <select wire:model.defer="sport"
                            class="text-gray-600 block border-gray-300 mt-1 rounded-md w-full focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            name="sport" id="sport">
                            <option disabled>Select a sport</option>
                            @forelse ($sports as $sport)
                                <option value="{{ $sport }}">
                                    {{ ucwords(str_replace('_', ' ', $sport)) }}
                                </option>
                            @empty
                                <option>There are no in-season sports right now.</option>
                            @endforelse
                        </select>
                    </div>
                </div>

                {{-- regions error --}}
                @error('sport')
                    <span class="block mt-1 text-red-500 text-xs">{{ $message }}</span>
                @enderror

                {{-- regions --}}
                <div class="mt-5 mb-1 font-medium text-sm">
                    <x-input-label for="regions" :value="__('Regions')" />

                    <div class="flex items-center space-x-2 mt-1">
                        <x-checkbox model="regions" value="us" />
                        <x-checkbox model="regions" value="uk" />
                        <x-checkbox model="regions" value="eu" />
                        <x-checkbox model="regions" value="au" />
                    </div>
                </div>

                {{-- wrong value regions error/ repeated regions --}}
                @error('regions.*')
                    <span class="block mt-1 text-red-500 text-xs">{{ $message }}</span>
                @enderror
                {{-- no value regions error/ max. four elements in array --}}
                @error('regions')
                    <span class="block mt-1 text-red-500 text-xs">{{ $message }}</span>
                @enderror

                {{-- slider --}}
                <div class="mt-5 mb-1 font-medium text-sm">
                    <x-input-label for="myRange" :value="__('Min. Over Value')" />

                    <div x-data="{ overvalue: @entangle('overValue').defer }" class="flex items-center">
                        <input x-model="overvalue" type="range" min="1" max="100" id="myRange"
                            class="w-1/2">

                        <div class="ml-4 text-sm">
                            <span x-text="overvalue"></span> %
                        </div>
                    </div>
                </div>

                {{-- over value error --}}
                @error('overValue')
                    <span class="block mt-1 text-red-500 text-xs">{{ $message }}</span>
                @enderror

                <button
                    class="inline-flex items-center px-4 py-2 bg-blue-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 active:bg-blue-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mt-4">
                    <svg wire:loading wire:target="getValueBets" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>

                    <span>
                        Find
                    </span>
                </button>
            </form>

        </x-find-value-bets-card>

        {{-- check if there are any matches offering value bets --}}
        {{-- this if statement is here to prevent error of trying to loop through a null object --}}
        {{-- since $matches is null before calling getValueBets() --}}
        @if (isset($matches))
            <div class="mb-16 mx-auto sm:max-w-md w-11/12">
                @forelse ($matches as $match)
                    @foreach ($match['value_bets'] as $outcome => $valueBets)
                        @foreach ($valueBets as $bookie => $stats)
                            <x-value-bet-card :match="$match" :outcome="$outcome" odd-format="decimal"
                                :stats="$stats" :bookie="$bookie" />
                        @endforeach
                    @endforeach
                @empty
                    <p>No value bets found for this sport/region.</p>
                @endforelse
            </div>
        @endif

    </div>
</div>
