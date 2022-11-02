<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Bets / Stats') }}
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto py-4 px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 md:grid-cols-4 sm:gap-10">

            {{-- total bets --}}
            <x-stat-card name='Total Bets' :value="$totalBets" />

            {{-- total winning bets --}}
            <x-stat-card name='Total Winning Bets' :value="$totalWinBets" />

            {{-- total losing bets --}}
            <x-stat-card name='Total Losing Bets' :value="$totalLossBets" />

            {{-- total NA bets --}}
            <x-stat-card name='Total N/A Bets' :value="$totalNaBets" />

            {{-- average odds --}}
            <x-stat-card name="Average Odds ({{ auth()->user()->odd_type }})" :value="auth()->user()->odd_type === 'american' && $averageOdds > 0 ? '+' . $averageOdds : $averageOdds" />

            {{-- implied probability --}}
            <x-stat-card name='Implied Probability' :value="$impliedProbability . '%'" />

            {{-- total gains --}}
            <x-stat-card name='Total Gains' :value="(new NumberFormatter('en_US', NumberFormatter::CURRENCY))->formatCurrency($totalGains, 'USD')" />

            {{-- total losses --}}
            <x-stat-card name='Total Losses' :value="(new NumberFormatter('en_US', NumberFormatter::CURRENCY))->formatCurrency($totalLosses, 'USD')" />

            {{-- net profit --}}
            <x-stat-card name='Net Profit' :value="(new NumberFormatter('en_US', NumberFormatter::CURRENCY))->formatCurrency(
                $totalGains - $totalLosses,
                'USD',
            )" />

            {{-- biggest bet --}}
            <x-stat-card name='Biggest Bet' :value="(new NumberFormatter('en_US', NumberFormatter::CURRENCY))->formatCurrency($biggestBet, 'USD')" />

            {{-- biggest payoff --}}
            <x-stat-card name='Biggest Payoff' :value="(new NumberFormatter('en_US', NumberFormatter::CURRENCY))->formatCurrency($biggestPayoff, 'USD')" />

            {{-- biggest loss --}}
            <x-stat-card name='Biggest Loss' :value="(new NumberFormatter('en_US', NumberFormatter::CURRENCY))->formatCurrency($biggestLoss, 'USD')" />

        </div>

        {{-- charts --}}
        @if ($totalBets)
            <div>
                {{-- results chart --}}
                <div class="max-w-md mx-auto mt-6 w-full">
                    <canvas id="resultChart"></canvas>
                </div>

                {{-- net profit chart --}}
                <div class="max-w-2xl mx-auto mt-8 w-full">
                    <canvas id="netProfit" style="width: 100%; height: 100%; width: 400px; height: 290px;"></canvas>
                </div>

                {{-- Odds/results stacked bar chart --}}
                <div class="max-w-2xl mx-auto mt-8 w-full">
                    <canvas id="oddsResultsDist"
                        style="width: 100%; height: 100%; width: 400px; height: 340px;"></canvas>
                </div>
            </div>
        @endif

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Results chart 
            var resultLabels = ["Wins", "Losses", "N/A"];
            var resultData = {{ json_encode($betResults) }};
            var barColors = [
                "green",
                "red",
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
                                text: 'Total Number of Bets'
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
                        data: {{ json_encode( isset($resultCountProbRange['losses']) ? $resultCountProbRange['losses'] : array_fill(0, 10, 0) ) }},
                        backgroundColor: "red",
                    },
                    {
                        label: 'Wins',
                        data: {{ json_encode( isset($resultCountProbRange['wins']) ? $resultCountProbRange['wins'] : array_fill(0, 10, 0) ) }},
                        backgroundColor: "green",
                    },
                    {
                        label: 'N/A',
                        data: {{ json_encode( isset($resultCountProbRange['na']) ? $resultCountProbRange['na'] : array_fill(0, 10, 0) ) }},
                        backgroundColor: "gray",
                    },
                ]
            };

            new Chart("oddsResultsDist", {
                type: 'bar',
                data: data,
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Implied Probability of Bet Results'
                        },
                    },
                    responsive: true,
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
        </script>
    @endpush
</x-app-layout>
