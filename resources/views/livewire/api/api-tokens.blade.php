<div>
    <x-slot:title>
        API Tokens | Betsjournal
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('API Tokens') }}
        </h2>
    </x-slot>

    <x-auth-card class="mb-4">

        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

        @if (session()->has('success'))
            <x-flash-category class="bg-green-300" status="success" />
        @endif

        <p class="text-xl font-semibold">API Tokens</p>

        <p class="font-medium text-sm text-gray-700">
            Manage your personal access tokens.
        </p>

        <div class="mt-2 mb-6">
            <a href="/docs/index.html" target="_blank" class="text-sm text-blue-400">
                See API docs &rarr;
            </a>
        </div>

        <form wire:submit.prevent="createToken">

            <!-- tokens -->
            <div>
                <x-input-label for="token_name" :value="__('Token Name')" />

                <x-text-input wire:model="token_name" id="token_name" class="block mt-1 w-full" type="text" required
                    autofocus />
            </div>

            {{-- tokens list --}}
            <div class="my-4 space-y-1 border-gray-200 border-t-2 mt-6 pt-4">
                @forelse (auth()->user()->tokens as $token)
                    <div class="border border-gray-400 flex items-center justify-between px-3 py-2 rounded text-sm hover:bg-gray-200"
                        wire:key="item-{{ $token->id }}">

                        <div class="flex items-center">
                            <p class="font-semibold hover:text-blue-500 text-gray-700">
                                {{ $token->name }}
                            </p>
                        </div>

                        <button wire:click="confirmDelete({{ $token->id }})" type="button">
                            <x-close-svg />
                        </button>

                    </div>

                @empty
                    <p>You have no tokens yet.</p>
                @endforelse
            </div>

            <div class="flex items-center justify-end mt-4">
                <a href="{{ route('bets.index', ['page' => request('page')]) }}" class="text-sm text-blue-500 mr-4">
                    Back
                </a>

                <x-primary-button>
                    {{ __('Create') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>

    {{-- delete token modal --}}
    <form wire:submit.prevent="deleteToken">
        <x-modal wire:model.defer="showDeleteModal" delete="{{ true }}">

            <x-slot name="title">
                Are you sure?
            </x-slot>

            <x-slot name="message">
                <p>
                    Do you really wish to delete this token?
                </p>

                <p class="font-bold mt-1">
                    {{ $currentToken->name }}
                </p>
            </x-slot>

            <x-slot name="buttons">
                <x-primary-button wire:click="$set('showDeleteModal', false)" type="button">
                    Cancel
                </x-primary-button>

                <x-primary-button class="bg-red-900 hover:bg-red-800 ml-2">
                    Delete
                </x-primary-button>
            </x-slot>
        </x-modal>
    </form>

    {{-- personal access token modal --}}
    <x-modal wire:model.defer="showTokenModal" delete="{{ false }}">

        <x-slot name="title">
            Personal Access Token
        </x-slot>

        <x-slot name="message">

            <p>
                Please copy your API token:
            </p>

            <p class="bg-gray-700 py-4 px-2 rounded-sm text-center text-gray-200 overflow-auto text-xs mt-4">
                {{ session('token') }}
            </p>

        </x-slot>

        <x-slot name="buttons">
            <x-primary-button wire:click="$set('showTokenModal', false)" type="button">
                Hide
            </x-primary-button>
        </x-slot>
    </x-modal>

</div>
