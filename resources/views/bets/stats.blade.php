<x-app-layout>
    <x-slot:title>
        Bet Stats | Betsjournal
        </x-slot>

        <x-slot name="header">
            <h2 class="font-semibold text-sm text-white leading-tight">
                {{ __('Bets / Stats') }}
            </h2>
        </x-slot>

        <div class="max-w-5xl mx-auto py-4 px-4 sm:px-6 lg:px-8">

            {{-- bet links --}}
            <div class="sm:flex sm:items-center sm:justify-between mb-14 mt-1">

                <div class="flex items-center mb-4 sm:mb-0">

                    {{-- new bet link --}}
                    <a href="{{ route('bets.create') }}"
                        class="bg-blue-900 font-semibold hover:opacity-75 py-2 rounded-lg text-center text-white w-1/2 sm:w-20">
                        <p class="text-sm">
                            New Bet
                        </p>
                    </a>

                    {{-- stats link --}}
                    <a href="{{ route('bets.index') }}"
                        class="ml-4 bg-blue-900 font-semibold hover:opacity-75 py-2 rounded-lg text-center text-white w-1/2 sm:w-20">
                        <p class="text-sm">
                            All
                        </p>
                    </a>

                </div>

                {{-- categories selector --}}
                <div>

                    <form class="flex items-center" action="/stats" method="GET">

                        <!-- categories -->
                        @if (auth()->user()->categories->count())
                            <select multiple name="categories[]" id="categories"
                                class="text-gray-600 block border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-[42px] rounded-md w-full sm:w-48">

                                @foreach (auth()->user()->categories as $category)
                                    <option {{ in_array($category->id, request('categories') ?? []) ? 'selected' : '' }}
                                        value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit"
                                class="ml-4 bg-blue-900 font-semibold hover:opacity-75 py-2 rounded-lg text-center text-white w-20">
                                <p class="text-sm">
                                    Filter
                                </p>
                            </button>
                        @endif

                    </form>

                </div>
            </div>

            <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 md:grid-cols-4 sm:gap-10">

                {{-- total bets --}}
                <x-stat-card name='Total Bets' :value="$totalBets" />

                {{-- total winning bets --}}
                <x-stat-card name='Total Winning Bets' :value="$totalWinBets" />

                {{-- total losing bets --}}
                <x-stat-card name='Total Losing Bets' :value="$totalLossBets" />

                {{-- total NA bets --}}
                <x-stat-card name='Total N/A Bets' :value="$totalNaBets" />

                {{-- total CO bets --}}
                <x-stat-card name='Total CO Bets' :value="$totalCOBets" />

                {{-- average odds --}}
                <x-stat-card name="Average Odds ({{ auth()->user()->odd_type }})" :value="auth()->user()->odd_type === 'american' && $averageOdds > 0
                    ? '+' . $averageOdds
                    : $averageOdds" />

                {{-- implied probability --}}
                <x-stat-card name='Implied Probability' :value="$impliedProbability . '%'" />

                {{-- actual probability --}}
                <x-stat-card name='Actual Probability' :value="$actualProbability . '%'" />

                {{-- total gains --}}
                <x-stat-card name='Total Gains' :value="(new NumberFormatter('en_US', NumberFormatter::CURRENCY))->formatCurrency(
                    $totalGains,
                    'USD',
                )" />

                {{-- total losses --}}
                <x-stat-card name='Total Losses' :value="(new NumberFormatter('en_US', NumberFormatter::CURRENCY))->formatCurrency(
                    $totalLosses,
                    'USD',
                )" />

                {{-- net profit --}}
                <x-stat-card name='Net Profit' :value="(new NumberFormatter('en_US', NumberFormatter::CURRENCY))->formatCurrency(
                    $totalGains - $totalLosses,
                    'USD',
                )" />

                {{-- biggest bet --}}
                <x-stat-card name='Biggest Bet' :value="(new NumberFormatter('en_US', NumberFormatter::CURRENCY))->formatCurrency(
                    $biggestBet,
                    'USD',
                )" />

                {{-- biggest payout --}}
                <x-stat-card name='Biggest Payout' :value="(new NumberFormatter('en_US', NumberFormatter::CURRENCY))->formatCurrency(
                    $biggestPayout,
                    'USD',
                )" />

                {{-- biggest loss --}}
                <x-stat-card name='Biggest Loss' :value="(new NumberFormatter('en_US', NumberFormatter::CURRENCY))->formatCurrency(
                    $biggestLoss,
                    'USD',
                )" />

            </div>

            {{-- charts html --}}
            @if ($totalBets)
                <div class="border-t-2 border-gray-300 mt-10 pt-10">
                    {{-- results chart --}}
                    <div class="bg-white h-96 max-w-2xl mx-auto p-4 rounded-lg shadow-md w-full">
                        <canvas id="resultChart"></canvas>
                    </div>

                    {{-- net profit chart --}}
                    <div class="bg-white h-96 max-w-2xl mt-12 mx-auto p-4 rounded-lg shadow-md w-full">
                        <canvas id="netProfit"></canvas>
                    </div>

                    {{-- Odds/results stacked bar chart --}}
                    <div class="bg-white h-96 max-w-2xl mt-12 mx-auto p-4 rounded-lg shadow-md w-full">
                        <canvas id="oddsResultsDist"></canvas>
                    </div>

                    {{-- monthly profit bar chart --}}
                    <div class="bg-white max-w-2xl mb-20 mt-12 mx-auto p-4 rounded-lg shadow-md w-full">
                        <div>
                            <label for="yearSelector" class="font-bold text-sm text-gray-600">
                                Year:
                            </label>
                            <select id="yearSelector" class="rounded-md ml-2 text-sm">
                                @foreach ($monthlyProfit as $year => $value)
                                    <option value="{{ $year }}" {{ $loop->first ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="h-96">
                            <canvas id="monthlyProfit"></canvas>
                        </div>
                    </div>
                </div>
            @endif

        </div>


        {{-- charts js --}}
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // Results chart 
                var resultLabels = ["Wins", "Losses", "N/A", "CO"];
                var resultData = {{ json_encode($betResultsSort) }};
                var barColors = [
                    "green",
                    "red",
                    "purple",
                    "gray"
                ];

                new Chart("resultChart", {
                    type: "pie",
                    data: {
                        labels: resultLabels,
                        datasets: [{
                            backgroundColor: barColors,
                            data: resultData
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: "Results"
                            }
                        }
                    }
                });

                // Net profit chart
                var cumBets = {{ json_encode(array_keys($netProfit)) }};
                var netProfitArr = {{ json_encode(array_values($netProfit)) }};

                new Chart("netProfit", {
                    type: "line",
                    data: {
                        labels: cumBets,
                        datasets: [{
                            backgroundColor: "rgb(30 58 138)",
                            borderColor: "rgb(30 58 138)",
                            data: netProfitArr
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: "Net Profit"
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Total Number of Resolved Bets'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Net Profit (USD)'
                                }
                            }
                        }
                    }
                });


                // Odds/results stacked bar chart

                // percentage range
                const labels = [
                    '0 - 10',
                    '10 - 20',
                    '20 - 30',
                    '30 - 40',
                    '40 - 50',
                    '50 - 60',
                    '60 - 70',
                    '70 - 80',
                    '80 - 90',
                    '90 - 100',
                ]

                const data = {
                    labels: labels,

                    datasets: [{
                            label: 'Losses',
                            data: {{ json_encode($resultCountProbRange['losses']) }},
                            backgroundColor: "red",
                        },
                        {
                            label: 'Wins',
                            data: {{ json_encode($resultCountProbRange['wins']) }},
                            backgroundColor: "green",
                        },
                        {
                            label: 'N/A',
                            data: {{ json_encode($resultCountProbRange['na']) }},
                            backgroundColor: "purple",
                        },
                        {
                            label: 'CO',
                            data: {{ json_encode($resultCountProbRange['co']) }},
                            backgroundColor: "gray",
                        }
                    ]
                };

                new Chart("oddsResultsDist", {
                    type: 'bar',
                    data: data,
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Implied Probability of Bet Results'
                            },
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Implied Probability %'
                                },
                                stacked: true,
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Number of Bets'
                                },
                                stacked: true
                            }
                        }
                    }
                });

                var months = [
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                    'June',
                    'July',
                    'August',
                    'September',
                    'October',
                    'November',
                    'December'
                ];

                var yearSelector = document.getElementById("yearSelector");
                var monthlyProfitData = {!! json_encode($monthlyProfit) !!};
                var chart = null;

                yearSelector.addEventListener("change", function() {
                    var selectedYear = yearSelector.value;
                    var profit = monthlyProfitData[selectedYear];

                    var barColors = [];
                    for (var key in profit) {
                        barColors.push(profit[key] >= 0 ? 'green' : 'red');
                    }

                    if (chart) {
                        chart.destroy();
                    }

                    chart = new Chart("monthlyProfit", {
                        type: "bar",
                        data: {
                            labels: months,
                            datasets: [{
                                backgroundColor: barColors,
                                data: profit
                            }]
                        },
                        options: {
                            maintainAspectRatio: false,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Monthly Profit'
                                },
                                legend: {
                                    display: false
                                },
                            },
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Month'
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: 'Profit (USD)'
                                    }
                                }
                            }
                        }
                    });
                });

                // Trigger the change event so that the chart is initialized with the default year
                yearSelector.dispatchEvent(new Event("change"));
            </script>
        @endpush
</x-app-layout>
