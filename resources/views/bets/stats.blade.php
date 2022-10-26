<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Stats') }}
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

        <div class="max-w-lg mx-auto mt-6">
            <canvas id="myChart"></canvas>
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            var xValues = ["Losses", "Wins", "N/A"];
            var yValues = {{ json_encode($betResults) }};
            var barColors = [
                "red",
                "green",
                "gray"
            ];

            new Chart("myChart", {
                type: "pie",
                data: {
                    labels: xValues,
                    datasets: [{
                        backgroundColor: barColors,
                        data: yValues
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
        </script>
    @endpush
</x-app-layout>
