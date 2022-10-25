<nav x-data="{ open: false }" class="bg-blue-900 text-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">

                        {{-- journal icon --}}
                        <svg class="w-5" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="Page-1" stroke="none" stroke-width="1" fill="white" fill-rule="evenodd">
                                <g id="icon-shape">
                                    <path
                                        d="M2,1.99079514 C2,0.891309342 2.89706013,0 4.00585866,0 L14.9931545,0 C15.5492199,0 16,0.443864822 16,1 L16,2 L5.00247329,2 C4.44882258,2 4,2.44386482 4,3 C4,3.55228475 4.44994876,4 5.00684547,4 L16.9931545,4 C17.5492199,4 18,4.44463086 18,5.00087166 L18,18.0059397 C18,19.1072288 17.1054862,20 16.0059397,20 L3.99406028,20 C2.8927712,20 2,19.1017876 2,18.0092049 L2,1.99079514 Z M6,4 L10,4 L10,12 L8,10 L6,12 L6,4 Z"
                                        id="Combined-Shape"></path>
                                </g>
                            </g>
                        </svg>

                        {{-- website/brand name --}}
                        <div>
                            <h3 class="ml-1.5 sm:pt-0 text-md">
                                <strong>Bets</strong>journal
                            </h3>
                        </div>
                    </a>
                </div>

                {{-- larger screen links for all visitors (registered or not) --}}

                {{-- about page link --}}
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('about')" :active="request()->routeIs('about')">
                        {{ __('About') }}
                    </x-nav-link>
                </div>

                {{-- Odds Comparison page link --}}
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('odds-comparison')" :active="request()->routeIs('odds-comparison')">
                        {{ __('Odds Comparison') }}
                    </x-nav-link>
                </div>

                {{-- odd converter page link --}}
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('odd-converter')" :active="request()->routeIs('odd-converter')">
                        {{ __('Odd Converter') }}
                    </x-nav-link>
                </div>
            </div>

            {{-- larger screen registered user dropdown --}}
            @auth
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <x-dropdown align="right" width="48">

                        {{-- username button to dropdown --}}
                        <x-slot name="trigger">
                            <button
                                class="flex items-center text-sm font-medium text-white hover:text-gray-300 hover:border-gray-300 focus:outline-none focus:text-gray-400 focus:border-gray-300 transition duration-150 ease-in-out">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        {{-- dropdown links --}}
                        <x-slot name="content">

                            {{-- user profile --}}
                            <x-dropdown-link :href="route('edit-profile')" :active="request()->routeIs('edit-profile')">
                                {{ __('Edit Profile') }}
                            </x-dropdown-link>

                            {{-- bets index --}}
                            <x-dropdown-link :href="route('bets.index')" :active="request()->routeIs('bets.index')">
                                {{ __('Bets') }}
                            </x-dropdown-link>

                            {{-- bets create --}}
                            <x-dropdown-link :href="route('bets.create')" :active="request()->routeIs('bets.create')">
                                {{ __('New Bet') }}
                            </x-dropdown-link>

                            {{-- bets stats --}}
                            <x-dropdown-link :href="route('bets.stats')" :active="request()->routeIs('bets.stats')">
                                {{ __('Stats') }}
                            </x-dropdown-link>

                            {{-- logout --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')" :active="request()->routeIs('logout')"
                                    onclick="event.preventDefault();
                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>

                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                {{-- larger screen un-registered user links --}}
                <div class="flex">
                    {{-- login --}}
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
                            {{ __('Log In') }}
                        </x-nav-link>
                    </div>

                    {{-- register --}}
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link :href="route('register')" :active="request()->routeIs('register')">
                            {{ __('Register') }}
                        </x-nav-link>
                    </div>
                </div>

            @endauth

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-black hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-black transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive/Mobile Navigation Menu -->
    @auth

        <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">

            <div class="py-2 space-y-1">
                {{-- about page link --}}
                <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')">
                    {{ __('About') }}
                </x-responsive-nav-link>

                {{-- Odds Comparison page link --}}
                <x-responsive-nav-link :href="route('odds-comparison')" :active="request()->routeIs('odds-comparison')">
                    {{ __('Odds Comparison') }}
                </x-responsive-nav-link>

                {{-- odd converter page link --}}
                <x-responsive-nav-link :href="route('odd-converter')" :active="request()->routeIs('odd-converter')">
                    {{ __('Odd Converter') }}
                </x-responsive-nav-link>
            </div>

            {{-- mobile screen registered user dropdown --}}
            <div class="pt-3 pb-1 border-t border-gray-200">

                {{-- user info --}}
                <x-responsive-nav-link :href="route('edit-profile')" :active="request()->routeIs('edit-profile')">
                    <div class="text-xs">{{ Auth::user()->name }}</div>
                    <div class="text-[10px] text-gray-400">{{ Auth::user()->email }}</div>
                </x-responsive-nav-link>
                
                {{-- bets index --}}
                <x-responsive-nav-link :href="route('bets.index')" :active="request()->routeIs('bets.index')">
                    {{ __('Bets') }}
                </x-responsive-nav-link>

                {{-- bets create --}}
                <x-responsive-nav-link :href="route('bets.create')" :active="request()->routeIs('bets.create')">
                    {{ __('New Bet') }}
                </x-responsive-nav-link>

                {{-- bets stats --}}
                <x-responsive-nav-link :href="route('bets.stats')" :active="request()->routeIs('bets.stats')">
                    {{ __('Stats') }}
                </x-responsive-nav-link>

                <div>
                    {{-- logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
            <!-- Responsive Settings Options -->
            <div class="py-2">

                {{-- about page link --}}
                <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')">
                    {{ __('About') }}
                </x-responsive-nav-link>

                {{-- Odds Comparison page link --}}
                <x-responsive-nav-link :href="route('odds-comparison')" :active="request()->routeIs('odds-comparison')">
                    {{ __('Odds Comparison') }}
                </x-responsive-nav-link>

            </div>

            {{-- mobile screen un-registered user links --}}
            <div class="border-t pt-1 border-gray-200">
                <div class="py-1">

                    {{-- log in --}}
                    <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                        {{ __('Log In') }}
                    </x-responsive-nav-link>

                    {{-- register --}}
                    <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        </div>
    @endauth

</nav>
