<div>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Bets / All') }}
        </h2>
    </x-slot>


    <div class="max-w-5xl mx-auto py-4 px-4 sm:px-6 lg:px-8 space-y-4 bg-gray-100">

        {{-- bet links --}}
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="flex items-center mb-4 sm:mb-0">

                {{-- new bet link --}}
                <a href="{{ route('bets.create') }}"
                    class="bg-blue-900 font-semibold hover:opacity-75 py-2 rounded-lg text-center text-white w-1/2 sm:w-20">
                    <p class="text-sm">
                        New Bet
                    </p>
                </a>

                {{-- stats link --}}
                <a href="{{ route('bets.stats') }}"
                    class="ml-4 bg-blue-900 font-semibold hover:opacity-75 py-2 rounded-lg text-center text-white w-1/2 sm:w-20">
                    <p class="text-sm">
                        Stats
                    </p>
                </a>
            </div>

            {{-- search bar --}}
            <div class="flex sm:justify-center">
                <div class="mb-1 xl:w-96 w-full">
                    <div class="input-group relative flex items-stretch w-full rounded">
                        <input wire:model="search" type="search" name="search"
                            class="form-control relative flex-auto min-w-0 block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                            placeholder="Search..." aria-label="Search" aria-describedby="button-addon2">
                        <span
                            class="input-group-text hidden sm:flex items-center px-3 py-1.5 text-base font-normal text-gray-700 text-center whitespace-nowrap rounded"
                            id="basic-addon2">
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="search"
                                class="w-4" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path fill="currentColor"
                                    d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z">
                                </path>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- filters --}}
        <div class="flex justify-end sm:mr-4">
            <input wire:model="win" type="checkbox" name="win" id="win"
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <label for="win" class="ml-1 mr-4 text-sm text-gray-600">Win</label>

            <input wire:model="loss" type="checkbox" name="loss" id="loss"
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <label for="loss" class="ml-1 mr-4 text-sm text-gray-600">Loss</label>

            <input wire:model="na" type="checkbox" name="na" id="na"
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <label for="na" class="ml-1 sm:mr-6 text-sm text-gray-600">N/A</label>
        </div>

        <div class="flex items-center">

            {{-- categories setting --}}
            <a href="{{ route('bets.categories') }}" data-tippy-content="Manage Categories"
                class="bg-blue-900 font-semibold hover:opacity-75 p-4 rounded-lg text-center text-white">
                <svg class="w-4 mx-auto" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g stroke="none" stroke-width="1" fill="#f3f3ed" fill-rule="evenodd">
                        <g>
                            <path
                                d="M3.93830521,6.49683865 C3.63405147,7.02216933 3.39612833,7.5907092 3.23599205,8.19100199 L5.9747955e-16,9 L9.6487359e-16,11 L3.23599205,11.808998 C3.39612833,12.4092908 3.63405147,12.9778307 3.93830521,13.5031614 L2.22182541,16.363961 L3.63603897,17.7781746 L6.49683865,16.0616948 C7.02216933,16.3659485 7.5907092,16.6038717 8.19100199,16.7640079 L9,20 L11,20 L11.808998,16.7640079 C12.4092908,16.6038717 12.9778307,16.3659485 13.5031614,16.0616948 L16.363961,17.7781746 L17.7781746,16.363961 L16.0616948,13.5031614 C16.3659485,12.9778307 16.6038717,12.4092908 16.7640079,11.808998 L20,11 L20,9 L16.7640079,8.19100199 C16.6038717,7.5907092 16.3659485,7.02216933 16.0616948,6.49683865 L17.7781746,3.63603897 L16.363961,2.22182541 L13.5031614,3.93830521 C12.9778307,3.63405147 12.4092908,3.39612833 11.808998,3.23599205 L11,0 L9,0 L8.19100199,3.23599205 C7.5907092,3.39612833 7.02216933,3.63405147 6.49683865,3.93830521 L3.63603897,2.22182541 L2.22182541,3.63603897 L3.93830521,6.49683865 L3.93830521,6.49683865 Z M10,13 C11.6568542,13 13,11.6568542 13,10 C13,8.34314575 11.6568542,7 10,7 C8.34314575,7 7,8.34314575 7,10 C7,11.6568542 8.34314575,13 10,13 L10,13 Z">
                            </path>
                        </g>
                    </g>
                </svg>
            </a>

            <!-- categories -->
            @if (auth()->user()->categories->count())
                <div class="ml-4 w-full">
                    <x-input-label for="categories" :value="__('Categories')" />

                    <select wire:model="categories" multiple id="categories"
                        class="block border-gray-300 h-20 mt-1 rounded-md w-full focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">

                        @foreach (auth()->user()->categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach

                    </select>
                </div>
            @endif

        </div>

        {{-- success flash message --}}
        <x-flash />

        {{-- display bets --}}
        @forelse ($bets as $bet)
            <x-bet-card :bet="$bet" />

        @empty

            <p>You haven't logged any bets yet.</p>
        @endforelse

        {{-- pagination --}}
        {{ $bets->links() }}
    </div>


    {{-- delete bet modal --}}
    <form wire:submit.prevent="deleteBet">
        <x-modal wire:model.defer="showDeleteModal" delete="{{ true }}">

            <x-slot name="title">
                Are you sure?
            </x-slot>

            <x-slot name="message">
                <p>
                    Do you really wish to delete this bet?
                </p>
                <p class="font-bold mt-1">
                    {{ $currentBet->match }}
                </p>
            </x-slot>

            <x-slot name="buttons">
                <x-primary-button wire:click="$set('showDeleteModal', false)" type="button">
                    Cancel
                </x-primary-button>

                <x-primary-button class="bg-red-900 hover:bg-red-800 ml-2">
                    Delete
                </x-primary-button>
            </x-slot>
        </x-modal>
    </form>

    {{-- tooltip --}}
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script>
        tippy('[data-tippy-content]');
    </script>
</div>
