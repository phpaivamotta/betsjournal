<x-app-layout>

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
                    {{-- <a href="{{ route('login') }}"
                        class="inline-block px-5 py-2 sm:px-7 sm:py-3 border-2 border-white text-white font-medium text-xs sm:text-sm leading-snug uppercase rounded hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out"
                        data-mdb-ripple="true" data-mdb-ripple-color="light">
                        Get started
                    </a> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="p-8 bg-gray-100">
        <div class="xw-2/3 max-w-md">
            <h2 class="font-bold text-2xl sm:text-3xl">
                Know where you stand.
            </h2>

            <p class="text-md">
                A big part of successful betting is keeping track of your profits and losses. This is where betsjournal
                comes in. We make it easy to record your bets anywhere, any time. We also make it easy for you to see
                your betting statistics by providing breaking-down your data for your, so that you can easily track your
                betting tactics. All you have to do is login and start using our features! Everything is absolutely
                free!

                {{-- <a href="{{ route('login') }}">
                    <span class="mt-2 text-blue-900 text-xs block underline">Start using now</span>
                </a> --}}

            </p>
        </div>

        <a href="{{ route('login') }}" class="mt-2 py-3 px-7 bg-blue-900 text-white text-sm">
            GET STARTED
        </a>

        {{-- <div>
            <img src="" alt="">
        </div> --}}
    </div>

</x-app-layout>
