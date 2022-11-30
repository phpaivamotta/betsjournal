<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('World Cup') }}
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto py-4 sm:px-6 lg:px-8 bg-gray-100">

        <div class="-mb-20" id="oddspedia-widget-competition-league-3">
            <script>
                window.oddspediaWidgetCompetitionLeague3 = {
                    api_token: "{{ config('services.oddspedia.token') }}",
                    type: "competition",
                    domain: "localhost",
                    selector: "oddspedia-widget-competition-league-3",
                    width: "0",
                    theme: "0",
                    odds_type: "1",
                    language: "en",
                    primary_color: "#283E5B",
                    accent_color: "#00B1FF",
                    font: "Roboto",
                    league_id: "3",
                    limit: "8",
                    show_odds: "true",
                };
            </script>
            <script src="https://widgets.oddspedia.com/js/widget/init.js?widgetId=oddspediaWidgetCompetitionLeague3" async></script>
        </div>

    </div>
</x-app-layout>
