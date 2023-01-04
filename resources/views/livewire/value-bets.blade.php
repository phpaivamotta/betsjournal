<div>
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

        <form wire:submit.prevent="getValueBets">

            <!-- Odd Format -->
            <p class="mt-5 mb-1 font-medium text-sm">
                Odd Format:
            </p>

            <div class="flex items-center">

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
                <label for="sports">In-season sports:</label>

                <div>
                    <select wire:model.defer="sport" class="rounded-sm block font-medium text-sm text-gray-700"
                        name="sport" id="sport">
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
                    <x-checkbox model="regions" value="us" />
                    <x-checkbox model="regions" value="uk" />
                    <x-checkbox model="regions" value="eu" />
                    <x-checkbox model="regions" value="au" />
                </div>
            </div>

            {{-- regions error --}}
            @error('regions')
                <span class="block mt-1 text-red-500 text-xs">{{ $message }}</span>
            @enderror

            {{-- slider --}}
            <div class="mt-5 mb-1 font-medium text-sm">
                <label for="sports">Min. Over Value:</label>

                <div 
                    x-data="{ overvalue: @entangle('overValue').defer }" 
                    class="flex items-center"
                >
                    <input 
                        x-model="overvalue" 
                        type="range" 
                        min="1" 
                        max="100" 
                        id="myRange"
                        class="w-1/2"
                    >

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

        {{-- betting tool info --}}
        <div class="flex items-center space-x-2 text-lg border-gray-200 border-t-2 mt-6 pt-4">
            <x-info-svg />

            <p>Tool Info</p>
        </div>

    </x-auth-card>


    {{-- check if there are any matches offering value bets --}}
    @if (isset($matches))

        <div class="sm:max-w-lg mx-auto">

            {{-- loop through each match --}}
            @forelse ($matches as $match)
                {{-- loop through each bookie offering value bets for the match --}}
                @foreach ($match['valueBets'] as $bookieName => $bookie)
                    {{-- loop through each bookie's value bet offerings (e.g., Home and Draw) --}}
                    @foreach ($bookie as $valueBetOffering => $valueBetStats)
                        {{-- pass necessary data to value bets card --}}
                        <x-value-bet-card class="mt-2 mb-2" :bookie-name="$bookieName" :match="$match" :value-bet-offering="$valueBetOffering"
                            :value-bet-stats="$valueBetStats" :odd-format="$oddFormat" />
                    @endforeach
                @endforeach

            @empty

                <p>No value bets found for this sport/region.</p>
            @endforelse

        </div>

    @endif

</div>
