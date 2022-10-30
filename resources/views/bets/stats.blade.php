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

        @if ($totalBets)
            <div>
                <div class="max-w-sm mx-auto mt-6 w-full">
                    <canvas id="resultChart"></canvas>
                </div>

                <div class="max-w-2xl mx-auto mt-8 w-full">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        @endif

    </div>

    {{-- @dd(json_encode(array_values($netProfit))) --}}

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Results chart 
            var resultLabels = ["Losses", "Wins", "N/A"];
            var resultData = {{ json_encode($betResults) }};
            var barColors = [
                "red",
                "green",
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
            var xValues = {{ json_encode(array_keys($netProfit)) }};
            var yValues = {{ json_encode(array_values($netProfit)) }};

            new Chart("myChart", {
                type: "line",
                data: {
                    labels: xValues,
                    datasets: [{
                        backgroundColor: "rgb(30 58 138)",
                        borderColor: "rgb(30 58 138)",
                        data: yValues
                    }]
                },
                options: {
                    plugins: {
                        legend: {display: false},
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
        </script>
    @endpush
</x-app-layout>
