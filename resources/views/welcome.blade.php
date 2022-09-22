<x-app-layout>

    {{-- hero section --}}
    <div class="-mt-1 relative overflow-hidden bg-no-repeat bg-cover bg-bottom h-80"
        style="
        background-position: 50%;
        background-image: url('https://cdn.pixabay.com/photo/2016/11/29/07/10/hand-1868015_1280.jpg');
      ">
        <div class="absolute top-0 right-0 bottom-0 left-0 w-full h-full overflow-hidden bg-fixed"
            style="background-color: rgba(48, 13, 203, 0.171)">
            <div class="flex justify-center items-center h-full">
                <div class="text-center text-white px-6 md:px-12">
                    <h1 class="text-3xl sm:text-4xl xl:text-6xl font-bold tracking-tight mb-4">
                        Track your bets <br />
                    </h1>
                    <p class="text-gray-200 text-sm sm:text-lg mb-6">
                        Easily stay on top of your bets from anywhere to gain better insight into your stategy.
                    </p>
                    <a href="{{ route('login') }}"
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
        <div class="md:w-2/3">
            <h2 class="font-bold text-2xl sm:text-3xl mb-2">
                Know where you stand.
            </h2>

            <p class="text-md">
                A big part of successful betting is keeping track of your profits and losses. This is where betsjournal
                comes in. We make it easy to record your bets anywhere, any time. We also make it easy for you to see
                your betting statistics by breaking-down your data for your, so that you can easily track your
                betting tactics. All you have to do is register and start using our features! Everything is absolutely
                free!
            </p>

        </div>

        {{-- image div --}}
        <div class="md:w-1/3 md:ml-10">
            <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fmedia.istockphoto.com%2Fvectors%2Fspreadsheet-on-computer-screen-flat-icon-financial-accounting-report-vector-id1025661672%3Fk%3D6%26m%3D1025661672%26s%3D612x612%26w%3D0%26h%3Ds2pr-oWJzDKFSMxo0h9jKM-cfvH4C3Nu1qSVgQrXIiE%3D&f=1&nofb=1"
                alt="spread-sheet-illustration" class="w-full mt-6 md:mt-0 rounded m-auto md:m-0">
        </div>
    </div>

    {{-- second container div/learn about betsjournal --}}
    <div class="bg-gray-300 md:flex md:items-center max-w-7xl mx-auto p-8">

        {{-- description div --}}
        <div class="md:w-2/3">
            <h2 class="font-bold text-2xl sm:text-3xl mb-2">
                Get statistical analysis on your bets.
            </h2>

            <p class="text-md">
                Let us analyze your betting data for you! You don't need to touch MS Excel or Google Sheets to have the
                most important information on your bets. We calculate your hit-rate, implied break-even odds and actual
                average betting odds so that you can have better insight. We also provide charts for easy visualization
                of your betting stats.
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
            <img src="https://cdn.pixabay.com/photo/2020/08/03/10/00/graph-5459708_1280.png"
                alt="statistical-analysis-illustration" class="w-full mt-6 md:mt-0 rounded m-auto md:m-0">
        </div>
    </div>

    {{-- third container div/value bets --}}
    <div class="md:flex md:items-center max-w-7xl mx-auto p-8">

        {{-- description div --}}
        <div class="md:w-2/3">
            <h2 class="font-bold text-2xl sm:text-3xl mb-2">
                Value bets.
            </h2>

            <p class="text-md">
                The best way make money in the betting world is to make sure you get the best odds! With our value bets
                tool, you can take advantage of bookie mistakes and profit off of their higher odds.
            </p>

            {{-- Value bets link --}}
            <p class="mt-6">
                <a href="{{ route('login') }}"
                    class="block md:inline-block text-center font-bold py-2 px-4 border rounded border-blue-900 text-blue-900 text-sm hover:bg-blue-900 hover:text-white">
                    See value bets
                </a>
            </p>
        </div>

        {{-- image div --}}
        <div class="md:w-1/3 md:ml-10">
            <img src="https://cdn.pixabay.com/photo/2016/10/30/21/56/mac-1784459__480.png"
                alt="spread-sheet-illustration" class="w-full mt-6 md:mt-0 rounded m-auto md:m-0">
        </div>
    </div>

</x-app-layout>
