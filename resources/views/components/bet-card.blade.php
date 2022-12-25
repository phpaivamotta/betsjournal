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

                {{-- date time --}}
                <div class="flex items center">
                    <p class="text-xs">
                        {{ \Carbon\Carbon::create($bet->match_date)->toFormattedDateString() }}
                    </p>

                    <p class="text-xs ml-3">
                        {{ \Carbon\Carbon::create($bet->match_time)->format('h:i A') }}
                    </p>
                </div>

                {{-- categories --}}
                <div class="flex items-center mt-2">
                    @foreach ($bet->categories as $category)
                        <span data-tippy-content="{{ $category->name }}"
                            class="bg-[{{ $category->color }}] w-4 h-4 rounded-full -mr-1"></span>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center ml-8 mt-2">
                {{-- edit icon --}}
                <a href="/bets/{{ $bet->id }}/edit">
                    <x-edit-icon />
                </a>

                {{-- delete icon --}}
                <button wire:click="confirmDelete({{ $bet->id }})" class="ml-3" type="button">
                    <x-delete-icon />
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
