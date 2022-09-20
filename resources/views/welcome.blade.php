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
                    <h1 class="text-3xl md:text-4xl xl:text-7xl font-bold tracking-tight mb-12">
                        Keep track of your bets <br />
                    </h1>
                    <a href="{{ route('login') }}" class="inline-block px-7 py-3 border-2 border-white text-white font-medium text-sm leading-snug uppercase rounded hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out"
                        data-mdb-ripple="true" data-mdb-ripple-color="light">
                        Get started
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
