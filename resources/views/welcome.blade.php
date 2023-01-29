<x-app-layout>

    {{-- success flash message --}}
    <x-flash />

    {{-- hero section --}}
    <div class="-mt-1 relative overflow-hidden bg-no-repeat bg-cover bg-bottom h-80 md:h-[28rem]"
        style="
        background-position: 50%;
        background-image: url({{ asset('img/hero.jpg') }});
      ">
        <div class="absolute top-0 right-0 bottom-0 left-0 w-full h-full overflow-hidden bg-fixed"
            style="background-color: rgba(48, 13, 203, 0.171)">
            <div class="flex justify-center items-center h-full">
                <div class="text-center text-white px-6 md:px-12">
                    <h1 class="text-3xl sm:text-4xl xl:text-6xl font-bold tracking-tight mb-4">
                        Track your bets <br />
                    </h1>
                    <p class="text-gray-100 text-sm sm:text-lg mb-6">
                        Easily stay on top of your bets from anywhere to gain better insight into your stategy.
                    </p>
                    <a href="{{ route('register') }}"
                        class="inline-block px-5 py-2 sm:px-7 sm:py-3 border-2 border-white text-white font-medium text-xs sm:text-sm leading-snug uppercase rounded hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out"
                        data-mdb-ripple="true" data-mdb-ripple-color="light">
                        Get started
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- first container div/intro to betsjournal --}}
    <div class="md:flex md:items-center max-w-7xl mx-auto p-8">

        {{-- description div --}}
        <div class="md:w-2/3 lg:px-20">
            <h2 class="font-bold text-2xl sm:text-3xl mb-2">
                Know where you stand.
            </h2>

            <p class="text-md">
                A big part of successful betting is keeping track of your profits and losses. This is where betsjournal
                comes in. We make it easy to record your bets anywhere, any time. We also make it easy for you to see
                your betting statistics by breaking-down your data for you, so that you can easily track your
                betting tactics. All you have to do is register and start using our features! Everything is absolutely
                free!
            </p>

        </div>

        {{-- image div --}}
        <div class="md:w-1/3 md:ml-10">
            <img src="{{ asset('img/computer-spreadsheet.jpg') }}" alt="spread-sheet-illustration"
                class="w-full mt-6 md:mt-0 rounded m-auto md:m-0">
        </div>
    </div>

    {{-- second container div/learn about betsjournal --}}
    <div class="bg-gray-300 md:flex md:items-center max-w-7xl mx-auto p-8">

        {{-- description div --}}
        <div class="md:w-2/3 lg:px-20">
            <h2 class="font-bold text-2xl sm:text-3xl mb-2">
                Get statistical analysis on your bets.
            </h2>

            <p class="text-md">
                Let us analyze your betting data for you! You don't need to touch MS Excel or Google Sheets to have the
                most important information on your bets. We calculate your hit-rate, implied odds, average betting odds
                (and much more!) so that you can have better insights. We also provide charts for easy visualization!
            </p>

            {{-- About website link --}}
            <p class="mt-6">
                <a href="{{ route('about') }}"
                    class="block md:inline-block text-center font-bold py-2 px-4 border rounded border-blue-900 text-blue-900 text-sm hover:bg-blue-900 hover:text-white">
                    Learn more
                </a>
            </p>
        </div>

        {{-- image div --}}
        <div class="md:w-1/3 md:mr-10 md:order-first">
            <img src="{{ asset('img/loose-graphs.png') }}" alt="statistical-analysis-illustration"
                class="w-full mt-6 md:mt-0 rounded m-auto md:m-0">
        </div>
    </div>

    {{-- third container div/value bets --}}
    <div class="md:flex md:items-center max-w-7xl mx-auto p-8">

        {{-- description div --}}
        <div class="md:w-2/3 lg:px-20">
            <h2 class="font-bold text-2xl sm:text-3xl mb-2">
                Odds Comparison.
            </h2>

            <p class="text-md">
                Before tracking your bets, you need to place them with a bookmaker first.
                You can look for the best odds for any sport or league using our Odds Comparison tool. This tool, which
                is powered by <i>Oddspedia</i>, lets you compare the odds for several different bookmakers. After
                finding the best odds, just click the bookmaker's link and place your bets! Then record them using
                <strong>Bets</strong>journal, of course!
            </p>

            {{-- Value bets link --}}
            <p class="mt-6">
                <a href="{{ route('odds-comparison') }}"
                    class="block md:inline-block text-center font-bold py-2 px-4 border rounded border-blue-900 text-blue-900 text-sm hover:bg-blue-900 hover:text-white">
                    Odds Comparison
                </a>
            </p>
        </div>

        {{-- image div --}}
        <div class="md:w-1/3 md:ml-10">
            <img src="{{ asset('img/computer-stock-up.webp') }}" alt="spread-sheet-illustration"
                class="w-full mt-6 md:mt-0 rounded m-auto md:m-0">
        </div>
    </div>

    {{-- features --}}
    <div class="relative max-w-7xl mx-auto p-8 bg-[#4A90E2] text-white">
        <h2 class="font-bold text-2xl sm:text-3xl mb-16 mt-6 text-center">
            Features
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3">

            {{-- tools feature --}}
            <div class="items-center mb-16 px-10">
                <x-calculator-icon />

                <p class="text-center font-semibold text-xl mt-4">
                    Tools
                </p>

                <p class="text-center text-md mt-2">
                    Betting calculators for boring bet math
                </p>
            </div>

            {{-- analysis feature --}}
            <div class="items-center mb-16 px-10">
                <x-analysis-icon />

                <p class="text-center font-semibold text-xl mt-4">
                    Analysis
                </p>

                <p class="text-center text-md mt-2">
                    Statistical analysis of all your bets
                </p>
            </div>

            <div class="items-center px-10">
                <x-valuebets-icon class="w-20 mx-auto" />

                <p class="text-center font-semibold text-xl mt-4">Value Bets</p>

                <p class="text-center text-md mt-2">
                    Find free value betting opportunities
                </p>
            </div>
        </div>
    </div>

    {{-- wave --}}
    <div class="relative max-w-7xl mx-auto p-8 -mt-1 h-48">
        <div class="custom-shape-divider-top-1672533867">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"
                preserveAspectRatio="none">
                <path
                    d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z"
                    class="shape-fill"></path>
            </svg>
        </div>
    </div>

    {{-- get started --}}
    <div class="max-w-7xl mx-auto p-8 h-48 mt-10">
        <p class="text-center">
            <a href="{{ route('register') }}"
                class="block md:inline-block text-center font-bold py-2 md:px-32 border rounded border-blue-900 text-blue-900 text-sm hover:bg-blue-900 hover:text-white">
                Get started
            </a>
        </p>
    </div>

</x-app-layout>
