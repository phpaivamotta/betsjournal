<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Edit Profile') }}
        </h2>
    </x-slot>

    <x-auth-card>
        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

        <form method="POST" action="{{ route('update-profile') }}">
            @method('PATCH')
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />

                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)"
                    autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />

                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                    :value="old('email', $user->email)" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                    autocomplete="new-password" placeholder="type new password..." />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" />
            </div>

            <!-- Odd Type -->
            <x-input-label class="mt-4" for="password_confirmation" :value="__('Odd Format')" />

            <div class="flex items-center mt-1">

                <input class="mr-1" type="radio" name="odd_type" id="american" @checked(old('odd_type', auth()->user()->odd_type) === 'american')
                    value="american">

                <x-input-label for="american" :value="__('American')" />

                <input class="ml-4 mr-1" type="radio" name="odd_type" id="decimal" @checked(old('odd_type', auth()->user()->odd_type) === 'decimal')
                    value="decimal">
                <x-input-label for="decimal" :value="__('Decimal')" />

            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ml-4">
                    {{ __('Update') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-app-layout>
