@props(['bet'])

@php
    $class = 'flex items-start justify-between p-4 rounded-lg shadow-md hover:shadow-lg';
    
    if ($bet->result === null) {
        $class = $class . ' bg-white';
    } elseif ($bet->result) {
        $class = $class . ' bg-emerald-200';
    } elseif (!$bet->result) {
        $class = $class . ' bg-red-200';
    }
@endphp

<div class="{{ $class }}">
    <div class="w-full">

        <header class="flex items-start justify-between mb-4 border-b border-gray-400 pb-2">

            <div>
                {{-- match --}}
                <h2 class="text-blue-900 text-lg font-bold">
                    {{ $bet->match }}
                </h2>

                <div class="flex items center">
                    {{-- date --}}
                    <p class="text-xs">
                        {{ \Carbon\Carbon::create($bet->match_date)->toFormattedDateString() }}
                    </p>

                    {{-- time --}}
                    <p class="text-xs ml-3">
                        {{ \Carbon\Carbon::create($bet->match_time)->format('h:i A') }}
                    </p>
                </div>

            </div>

            <div class="flex items-center ml-8 mt-2">
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
                <button wire:click="confirmDelete({{ $bet->id }})" class="ml-3" type="button">
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
            </div>
        </header>

        <main>
            <div class="flex items-center justify-between">

                <div>
                    {{-- bet pick --}}
                    <p>
                        <span class="text-md font-bold">{{ ucwords($bet->bet_pick) }}</span>
                    </p>

                    {{-- bet type --}}
                    <p class="text-xs">
                        {{ $bet->bet_type }}
                    </p>
                </div>

                <div>
                    {{-- bet odd --}}
                    <div class="flex items-center gap-2">

                        {{-- odds info tippy.js --}}
                        <span class="trippy-tippy">
                            <x-info-svg />
                        </span>

                        <p class="font-semibold">
                            @if (auth()->user()->odd_type === 'american')
                                <span class="text-sm">
                                    @if ($bet->american_odd > 0)
                                        @ {{ '+' . $bet->american_odd }}
                                    @else
                                        @ {{ $bet->american_odd }}
                                    @endif
                                </span>
                            @elseif (auth()->user()->odd_type === 'decimal')
                                <span class="text-sm">@ {{ $bet->decimal_odd }}</span>
                            @endif
                        </p>
                    </div>

                    {{-- implied probability --}}
                    <p class="text-xs text-right">
                        {{ $bet->impliedProbability() }}
                    </p>

                </div>

            </div>

            <p class="text-sm mt-4">
                {{ $bet->bookie }}
            </p>

            <p class="text-sm">
                {{ $bet->sport }}
            </p>

            <p class="text-sm mt-4">
                {{ $bet->bet_description }}
            </p>

        </main>

        <footer class="flex items-center space-x-20 border-t border-gray-400 mt-4 pt-2">
            {{-- bet size --}}
            <div>
                <p>
                    <span class="text-xs">Stake</span>
                </p>

                <p>
                    <span
                        class="text-md font-semibold">{{ (new NumberFormatter('en_US', NumberFormatter::CURRENCY))->formatCurrency($bet->bet_size, 'USD') }}</span>
                </p>

            </div>

            {{-- display result if there is one --}}
            @if (isset($bet->result))
                {{-- if win --}}
                @if ($bet->result)
                    {{-- bet payout --}}
                    <div>
                        <p>
                            <span class="text-xs">Payout:</span>
                        </p>

                        <p>
                            <span
                                class="text-md  font-semibold">{{ (new NumberFormatter('en_US', NumberFormatter::CURRENCY))->formatCurrency($bet->payout(), 'USD') }}</span>
                        </p>
                    </div>

                    {{-- if loss --}}
                @else
                    {{-- bet payout --}}
                    <div>
                        <p>
                            <span class="text-xs">Payout:</span>
                        </p>
                        <p>
                            <span class="text-md font-semibold">$0</span>
                        </p>
                    </div>
                @endif

                {{-- if there is no result yet --}}
            @else
                {{-- potential bet payout --}}
                <div>
                    <p>
                        <span class="text-xs">Potential Payout:</span>
                    </p>
                    <p>
                        <span
                            class="text-md font-semibold">{{ (new NumberFormatter('en_US', NumberFormatter::CURRENCY))->formatCurrency($bet->payout(), 'USD') }}</span>
                    </p>
                </div>
            @endif
        </footer>

    </div>

</div>
