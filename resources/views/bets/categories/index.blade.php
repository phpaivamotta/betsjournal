<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Bets / Categories / New') }}
        </h2>
    </x-slot>

    <x-auth-card class="mb-4">

        {{-- bet links --}}
        <div class="mb-10 mt-1">
            <div class="flex items-center mb-4">

                {{-- new bet link --}}
                <a href="{{ route('bets.index') }}"
                    class="bg-blue-900 font-semibold hover:opacity-75 py-2 rounded-lg text-center text-white w-1/2">
                    <p class="text-sm">
                        All
                    </p>
                </a>

                {{-- stats link --}}
                <a href="{{ route('bets.stats') }}"
                    class="ml-4 bg-blue-900 font-semibold hover:opacity-75 py-2 rounded-lg text-center text-white w-1/2">
                    <p class="text-sm">
                        Stats
                    </p>
                </a>
            </div>
        </div>

        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

        <form method="POST" action="{{ route('bets.categories.store') }}">
            @csrf

            <!-- categories -->
            <div>
                <x-input-label for="name" :value="__('Category')" />

                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                    required autofocus />
            </div>

            <!-- color -->
            <div class="mt-4">
                <x-input-label for="color" :value="__('Color')" />

                <x-text-input id="color" class="block mt-1" type="color" name="color" :value="old('color')"
                    required />
            </div>

            <div class="my-4">
                @forelse ($categories as $category)
                    <p class="bg-[{{ $category->color }}]">
                        {{ $category->name }}
                    </p>
                @empty
                    <p>No categories yet...</p>
                @endforelse
            </div>

            <div class="flex justify-end mt-4">
                <x-primary-button>
                    {{ __('Create') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-app-layout>
