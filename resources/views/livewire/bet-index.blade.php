<div>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Bets') }}
        </h2>
    </x-slot>


    <div class="max-w-5xl mx-auto py-4 px-4 sm:px-6 lg:px-8 space-y-4 bg-gray-100">

        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="flex items-center mb-4 sm:mb-0">
                {{-- new bet link --}}
                <a href="/bets/create"
                    class="bg-blue-900 font-semibold hover:opacity-75 py-2 rounded-lg text-center text-white w-1/2 sm:w-20">
                    <p class="text-sm">
                        New Bet
                    </p>
                </a>

                {{-- stats link --}}
                <a href="/stats"
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

        {{-- success flash message --}}
        <x-flash />

        @forelse ($bets as $bet)
            {{-- bet card --}}
            @php
                $class = "flex items-start justify-between p-4 rounded-lg shadow-md hover:shadow-lg";
                
                if ($bet->result === null) {
                    $class = $class . " bg-white";
                } else if ($bet->result) {
                    $class = $class . " bg-gradient-to-r from-emerald-600 to-cyan-600";
                } else if (! $bet->result) {
                    $class = $class . " bg-gradient-to-r  from-red-400 to-orange-300";
                }
            @endphp

            <div class="{{ $class }}">
                <div>
                    {{-- match --}}
                    <h2 class="text-blue-900 text-xl mb-4 font-bold">
                        {{ $bet->match }}
                    </h2>

                    {{-- bet size --}}
                    <p>
                        <span class="text-sm font-bold">Size:</span>
                        <span class="text-xs">${{ $bet->bet_size }}</span>
                    </p>

                    {{-- bet odd --}}
                    <p>
                        <span class="text-sm font-bold">Odd:</span>
                        @if (auth()->user()->odd_type === 'american')
                            <span class="text-xs">
                                @if ($bet->american_odd > 0)
                                    {{ '+' . $bet->american_odd . ' (' . auth()->user()->odd_type . ')' }}
                                @else
                                    {{ $bet->american_odd . ' (' . auth()->user()->odd_type . ')' }}
                                @endif
                            </span>
                        @elseif (auth()->user()->odd_type === 'decimal')
                            <span class="text-xs">{{ $bet->decimal_odd . ' (' . auth()->user()->odd_type . ')' }}</span>
                        @endif
                    </p>

                    {{-- disply result if there is one --}}
                    @if (isset($bet->result))
                        {{-- if win --}}
                        @if ($bet->result)
                            <p>
                                <span class="text-sm font-bold">Result: </span>
                                <span class="text-xs">Win</span>
                            </p>

                            {{-- bet payoff --}}
                            <p>
                                <span class="text-sm font-bold">Payoff:</span>
                                <span class="text-xs">${{ $bet->payoff() }}</span>
                            </p>

                            {{-- if loss --}}
                        @else
                            <p>
                                <span class="text-sm font-bold">Result: </span>
                                <span class="text-xs">Loss</span>
                            </p>

                            {{-- bet payoff --}}
                            <p>
                                <span class="text-sm font-bold">Payoff:</span>
                                <span class="text-xs">$0</span>
                            </p>
                        @endif

                        {{-- if there is no result yet --}}
                    @else
                        <p>
                            <span class="text-sm font-bold">Result: </span>
                            <span class="text-xs">N/A</span>
                        </p>

                        {{-- potential bet payoff --}}
                        <p>
                            <span class="text-sm font-bold">Potential Payoff:</span>
                            <span class="text-xs text-gray-500">${{ $bet->payoff() }}</span>
                        </p>
                    @endif

                    {{-- optional attributes --}}
                    @foreach ($optional_attributes as $optional)
                        @if (isset($bet->$optional))
                            <p>
                                <span class="text-sm font-bold">{{ ucwords(str_replace('_', ' ', $optional)) }}:
                                </span>
                                <span class="text-xs">{{ $bet->$optional }}</span>
                            </p>
                        @endif
                    @endforeach
                </div>

                <div class="ml-8">
                    {{-- edit icon --}}
                    <a href="/bets/{{ $bet->id }}/edit">
                        <svg class="w-4" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g stroke="none" stroke-width="1" fill="#9b9b9b" fill-rule="evenodd">
                                <g>
                                    <path
                                        d="M12.2928932,3.70710678 L0,16 L0,20 L4,20 L16.2928932,7.70710678 L12.2928932,3.70710678 Z M13.7071068,2.29289322 L16,0 L20,4 L17.7071068,6.29289322 L13.7071068,2.29289322 Z">
                                    </path>
                                </g>
                            </g>
                        </svg>
                    </a>

                    {{-- delete icon --}}
                    <button wire:click="confirmDelete({{ $bet->id }})" class="mt-4" type="button">
                        <svg class="w-4 "viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g stroke="none" stroke-width="1" fill="#d76565" fill-rule="evenodd">
                                <g>
                                    <path
                                        d="M2,2 L18,2 L18,4 L2,4 L2,2 Z M8,0 L12,0 L14,2 L6,2 L8,0 Z M3,6 L17,6 L16,20 L4,20 L3,6 Z M8,8 L9,8 L9,18 L8,18 L8,8 Z M11,8 L12,8 L12,18 L11,18 L11,8 Z">
                                    </path>
                                </g>
                            </g>
                        </svg>
                    </button>

                    {{-- delete icon TEST --}}
                    {{-- <button wire:click="$set('showEditModal', true)" class="mt-4" type="button">
                        <svg class="w-4 "viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="Page-1" stroke="none" stroke-width="1" fill="#d76565" fill-rule="evenodd">
                                <g id="icon-shape">
                                    <path
                                        d="M2,2 L18,2 L18,4 L2,4 L2,2 Z M8,0 L12,0 L14,2 L6,2 L8,0 Z M3,6 L17,6 L16,20 L4,20 L3,6 Z M8,8 L9,8 L9,18 L8,18 L8,8 Z M11,8 L12,8 L12,18 L11,18 L11,8 Z"
                                        id="Combined-Shape"></path>

                                </g>
                            </g>
                        </svg>
                    </button> --}}

                </div>

            </div>
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

                <x-primary-button class="bg-red-900 hover:bg-red-800 ml-1">
                    Delete
                </x-primary-button>
            </x-slot>
        </x-delete-modal>
    </form>

    {{-- <x-delete-modal wire:model.defer="showEditModal">

        <x-slot name="title">
            Edit Bet
        </x-slot>

        <x-slot name="message">
            Edit your bet!
        </x-slot>

        <x-slot name="buttons">
            <x-primary-button wire:click="$set('showEditModal', false)" type="button">
                Cancel
            </x-primary-button>

            <x-primary-button wire:click="update" class="bg-red-900 hover:bg-red-800 ml-1">
                Update
            </x-primary-button>
        </x-slot>

    </x-delete-modal> --}}


</div>
