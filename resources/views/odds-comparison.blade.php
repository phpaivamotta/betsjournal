<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Odds Comparison') }}
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto py-4 sm:px-6 lg:px-8 bg-gray-100">

        <div class="-mb-20 -mt-4" id="oddspedia-widget-odds-comparison-popular-false-sports-false-leagues-false">
            <script>
                window.oddspediaWidgetOddsComparisonPopularSportsLeagues = {
                    api_token: "6a80a6deba0271ff740e80493fab24d9d5570aec2ef24766c3ae173b8fff",
                    type: "odds-comparison",
                    domain: "localhost",
                    selector: "oddspedia-widget-odds-comparison-popular-false-sports-false-leagues-false",
                    width: "0",
                    theme: "0",
                    odds_type: "1",
                    language: "en",
                    primary_color: "#283E5B",
                    accent_color: "#00B1FF",
                    font: "Roboto",
                    logos: "true",
                    limit: "15",
                    popular: "false",
                    sports: "",
                    leagues: "",
                };
            </script>
            <script src="https://widgets.oddspedia.com/js/widget/init.js?widgetId=oddspediaWidgetOddsComparisonPopularSportsLeagues"
                async></script>
        </div>

    </div>
</x-app-layout>
