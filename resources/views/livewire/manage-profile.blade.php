<div>
    <x-slot:title>
        Profile | Betsjournal
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Edit Profile') }}
        </h2>
    </x-slot>

    <x-auth-card>
        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

        <x-post-updated-message />

        <form wire:submit.prevent="updateProfile">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />

                <x-text-input wire:model.defer="name" id="name" class="block mt-1 w-full" type="text" autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />

                <x-text-input wire:model.defer="email" id="email" class="block mt-1 w-full" type="email" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full" type="password" wire:model.defer="password"
                    autocomplete="new-password" placeholder="type new password..." />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    wire:model.defer="password_confirmation" />
            </div>

            <!-- Odd Type -->
            <x-input-label class="mt-4" :value="__('Odd Format')" />

            <div class="flex items-center mt-1">

                <input class="mr-1" type="radio" wire:model="odd_type" id="american" value="american">
                <x-input-label for="american" :value="__('American')" />

                <input class="ml-4 mr-1" type="radio" wire:model="odd_type" id="decimal" value="decimal">
                <x-input-label for="decimal" :value="__('Decimal')" />

            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button>
                    {{ __('Update') }}
                </x-primary-button>

                <x-primary-button type="button" wire:click="confirmDeleteProfile"
                    class="bg-red-900 hover:bg-red-800 ml-2">
                    {{ __('Delete') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>

    {{-- delete profile modal --}}
    <form wire:submit.prevent="deleteProfile">
        <x-modal wire:model.defer="showDeleteProfileModal" delete="{{ true }}">

            <x-slot name="title">
                Are you sure?
            </x-slot>

            <x-slot name="message">
                <p>
                    Once your account is deleted, you can no longer recover it.
                </p>
            </x-slot>

            <x-slot name="buttons">
                <x-primary-button wire:click="$set('showDeleteProfileModal', false)" type="button">
                    Cancel
                </x-primary-button>

                <x-primary-button class="bg-red-900 hover:bg-red-800 ml-2">
                    Delete
                </x-primary-button>
            </x-slot>
        </x-modal>
    </form>
</div>
