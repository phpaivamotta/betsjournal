<div>
    <x-slot:title>
        All Bets | Betsjournal
    </x-slot>

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
                            <x-search-icon />
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
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <label for="na" class="ml-1 mr-4 text-sm text-gray-600">N/A</label>

            <input wire:model="cashout" type="checkbox" name="cashout" id="cashout"
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <label for="cashout" class="ml-1 sm:mr-6 text-sm text-gray-600">CO</label>
        </div>

        <div class="flex items-center">

            <!-- categories -->
            @if (auth()->user()->categories->count())
                <div class="mr-4 w-full">

                    <select wire:model="categories" multiple id="categories"
                        class="text-gray-600 block border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-[42px] rounded-md w-full">

                        @foreach (auth()->user()->categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach

                    </select>
                </div>
            @endif

            {{-- categories setting --}}
            <a href="{{ route('bets.categories', ['page' => $this->page]) }}" data-tippy-content="Manage Categories"
                class="bg-blue-900 font-semibold hover:opacity-75 p-[13px] rounded-lg text-center text-white">
                <x-categories-icon />
            </a>

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

    {{-- resolve bet modal --}}
    <form wire:submit.prevent="resolveBet">
        <x-modal x-data="{ open: false }" wire:model.defer="showResolveModal" delete="{{ false }}">

            <x-slot name="title">
                Resolve
            </x-slot>

            <x-slot name="message">
                <p class="mt-1">
                    Resolve <span class="font-semibold">{{ $currentBet->match }}</span>:
                </p>

                <div>
                    <!-- result -->
                    <div class="flex items-center mt-4">
                        <input wire:model="result" x-on:click="open = false" class="mr-1" type="radio"
                            id="win" value=1>
                        <x-input-label for="win" :value="__('Win')" />

                        <input wire:model="result" x-on:click="open = false" class="ml-4 mr-1" type="radio"
                            id="loss" value=0>
                        <x-input-label for="loss" :value="__('Loss')" />

                        <input wire:model="result" x-on:click="open = true" class="ml-4 mr-1" type="radio"
                            id="cashout" value=2>
                        <x-input-label for="cashout" :value="__('CO')" />
                    </div>
                    {{-- result error --}}
                    @error('result')
                        <span class="block mt-1 text-red-500 text-xs">{{ $message }}</span>
                    @enderror

                    <!-- cashout -->
                    <div x-show="open" class="mt-4" style="display: none;">
                        <x-input-label for="cashout" :value="__('Cashout')" />

                        <x-text-input wire:model="cashoutAmount" id="cashout" class="block mt-1 w-full"
                            type="number" step="0.01" name="cashout" autofocus />
                    </div>
                    {{-- cash amount error --}}
                    @error('cashoutAmount')
                        <span class="block mt-1 text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </x-slot>

            <x-slot name="buttons">
                <x-primary-button x-on:click="open = false" wire:click="resetAttributes" type="button"
                    class="bg-red-900 hover:bg-red-800 mr-2">
                    Cancel
                </x-primary-button>

                <x-primary-button>
                    Resolve
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
