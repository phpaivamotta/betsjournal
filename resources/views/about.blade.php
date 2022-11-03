<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('About') }}
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto py-4 px-4 sm:px-6 lg:px-8">

        <div class="mb-6 w-full space-y-2">
            <h3 class="font-bold text-lg">Rundown</h3>

            <p class="text-sm">
                To get started recording your bets, just
                <a href="{{ route('register') }}"><span class="text-blue-500">register</span></a> an account and head over
                to <a href="{{ route('bets.create') }}"><span class="text-blue-500">Bets / New</span></a>. There, you can fill out the details of the bets you want to keep track of! If you
                haven't placed any bets yet, just use our <a href="{{ route('odds-comparison') }}"><span class="text-blue-500">Odds Comparison</span></a> tool to check out
                the bookies offering the best deals!
            </p>

            <p class="text-sm">
                <strong>Bets</strong>journal makes it easy for you to track your bets and stay on top of your earnings!
                You can see all of your bets and its details under <a href="{{ route('bets.index') }}"><span class="text-blue-500">Bets / All</span></a>. You can also checkout the analysis of your bets under <a href="{{ route('bets.stats') }}"><span class="text-blue-500">Bets / Stats</span></a>, no need to use excel!
            </p>
        </div>

        <div class="mb-6 w-full">
            <h3 class="font-bold text-lg">Odds Comparison</h3>

            <p class="text-sm">
                Our <a href="{{ route('odds-comparison') }}"><span class="text-blue-500">Odds Comparison</span></a> tool makes use of Oddspedia's odds comparison widget/API.
                This tool allows you to compare the odds given by several different bookies for a given match. After finding the best odds, you can just click the bookie's link and place your bet! 
            </p>
        </div>

        <div class="mb-6 w-full">
            <h3 class="font-bold text-lg">Odd Converter</h3>

            <p class="text-sm">
                Sometimes, you need to convert the odds given by a bookie from an american format to a decimal format. For this purpose, you can use our <a href="{{ route('odd-converter') }}"><span class="text-blue-500">Odd Converter</span></a> tool to convert your odds from american to decimal or vice-versa. 
            </p>
        </div>

        <h3 class="font-bold text-lg">Bets</h3>

        <div class="mb-6 w-full">
            <h3 class="font-semibold text-md">All</h3>

            <p class="text-sm">
                Under <a href="{{ route('bets.index') }}"><span class="text-blue-500">Bets / All</span></a> is where all of the bets you logged are displayed. Besides being able to see all the information recorded, you can filter your bets by result or content.
            </p>
        </div>

        <div class="mb-6 w-full">
            <h3 class="font-semibold text-md">Stats</h3>

            <p class="text-sm">
                Under <a href="{{ route('bets.stats') }}"><span class="text-blue-500">Bets / Stats</span></a> is where you can see the most important information regarding your bets. It is here that you can have an overview of your betting results and insights into your betting strategy. All without having to open Excel!  
            </p>
        </div>

    </div>

</x-app-layout>
