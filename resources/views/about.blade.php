<x-app-layout>
    <x-slot:title>
        About | Betsjournal
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('About') }}
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto py-4 px-4 sm:px-6 lg:px-8">

        <div class="mb-10 mt-10 w-full space-y-2">
            <h3 class="font-bold text-lg">Rundown</h3>

            <p class="text-sm">
                To get started recording your bets, just
                <a href="{{ route('register') }}"><span class="text-blue-500 font-semibold">register</span></a> an account and head over
                to <a href="{{ route('bets.create') }}"><span class="text-blue-500 font-semibold">Bets / New</span></a>. There, you can
                fill out the details of the bets you want to keep track of! If you
                haven't placed any bets yet, just use our <a href="{{ route('odds-comparison') }}"><span
                        class="text-blue-500 font-semibold">Odds Comparison</span></a> tool to check out
                the bookies offering the best deals!
            </p>

            <p class="text-sm">
                <strong>Bets</strong>journal makes it easy for you to track your bets and stay on top of your earnings!
                You can see all of your bets and its details under <a href="{{ route('bets.index') }}"><span
                        class="text-blue-500 font-semibold">Bets / All</span></a>. You can also checkout the analysis of your bets
                under <a href="{{ route('bets.stats') }}"><span class="text-blue-500 font-semibold">Bets / Stats</span></a>, no need
                to use excel!
            </p>
        </div>

        <div class="mb-10 w-full">
            <h3 class="font-bold text-lg">Odds Comparison</h3>

            <p class="text-sm">
                Our <a href="{{ route('odds-comparison') }}"><span class="text-blue-500 font-semibold">Odds Comparison</span></a> tool
                makes use of Oddspedia's odds comparison widget/API.
                This tool allows you to compare the odds given by several different bookies for a given match. After
                finding the best odds, you can just click the bookie's link and place your bet!
            </p>
        </div>

        <div class="mb-10 w-full">
            <h3 class="font-bold text-lg">Value Bets</h3>

            <p class="text-sm">
                Our <a href="{{ route('value-bets') }}"><span class="text-blue-500 font-semibold">Value Bets Finder</span></a> feature uses advanced algorithms to analyze odds from various bookmakers and identify betting opportunities where the odds being offered are higher than the probability of the event occurring. This can help you find value bets that have a higher potential return on investment. Simply select the event you want to bet on and let our Value Bet Finder do the work for you. With just a few clicks, you can place your bet with confidence knowing that you're getting the best value for your money.
            </p>
        </div>

        <div class="mb-10 w-full">
            <h3 class="font-bold text-lg">Odd Converter</h3>

            <p class="text-sm">
                Sometimes, you need to convert the odds given by a bookie from an american format to a decimal format.
                For this purpose, you can use our <a href="{{ route('odd-converter') }}"><span class="text-blue-500 font-semibold">Odd
                        Converter</span></a> tool to convert your odds from american to decimal or vice-versa.
            </p>
        </div>

        <div class="mb-10 w-full">
            <h3 class="font-bold text-lg">Payout Calculator</h3>

            <p class="text-sm">
                If you want to quickly calculate the potential payout of a bet, you can use our <a
                    href="{{ route('payout-calculator') }}"><span class="text-blue-500 font-semibold">Payout Calculator</span></a>
                tool to check the expected payout and profit.
            </p>
        </div>

        <div class="mb-10 w-full">
            <h3 class="font-bold text-lg">Margin Calculator</h3>

            <p class="text-sm">
                Bookies make their money by charging you a fee. This fee is called a margin. It is baked into the odds
                of the event and, if you are serious about betting and making a profit, you should look out for the
                bookies with the lowest margings. If you don't know how to calculate margins, just head over to our
                <a href="{{ route('margin-calculator') }}"><span class="text-blue-500 font-semibold">Margin Calculator</span></a>
                tool to check the how much "juice" you're being charged.
            </p>
        </div>

        <h3 class="font-bold text-lg">Bets</h3>

        <div class="mb-6 w-full">
            <h3 class="font-semibold text-md">All</h3>

            <p class="text-sm">
                Under <a href="{{ route('bets.index') }}"><span class="text-blue-500 font-semibold">Bets / All</span></a> is where
                all of the bets you logged are displayed. Besides being able to see all the information recorded, you
                can filter your bets by result or content.
            </p>
        </div>

        <div class="mb-10 w-full">
            <h3 class="font-semibold text-md">Stats</h3>

            <p class="text-sm">
                Under <a href="{{ route('bets.stats') }}"><span class="text-blue-500 font-semibold">Bets / Stats</span></a> is where
                you can see the most important information regarding your bets. It is here that you can have an overview
                of your betting results and insights into your betting strategy. All without having to open Excel!
            </p>
        </div>

    </div>

</x-app-layout>
