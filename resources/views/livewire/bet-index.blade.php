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
                            placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
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
                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <label for="win" class="ml-1 mr-4 text-sm text-gray-600">Win</label>

            <input wire:model="loss" type="checkbox" name="loss" id="loss"
                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <label for="loss" class="ml-1 mr-4 text-sm text-gray-600">Loss</label>

            <input wire:model="na" type="checkbox" name="na" id="na"
                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <label for="na" class="ml-1 sm:mr-6 text-sm text-gray-600">N/A</label>
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
        <x-delete-modal wire:model.defer="showDeleteModal">

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
        </x-delete-modal>
    </form>

    {{-- tooltip --}}
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script>
        tippy('.trippy-tippy', {
            content: 'Change odds type in Edit Profile',
            trigger: 'mouseenter click',
        });
    </script>
</div>
