<div>
    <x-slot:title>
        Categories | Betsjournal
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Bets / Categories') }}
        </h2>
    </x-slot>

    <x-auth-card class="mb-4">

        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

        @if (session()->has('warning'))
            <x-flash-category class="bg-red-300" status="warning" />
        @endif

        @if (session()->has('success'))
            <x-flash-category class="bg-green-300" status="success" />
        @endif

        <p class="text-xl font-semibold">Categories</p>

        <p class="font-medium text-sm text-gray-700 mb-10">
            Manage your bet categories.
        </p>

        <form wire:submit.prevent="create">

            <!-- categories -->
            <div>
                <x-input-label for="name" :value="__('Category')" />

                <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" required
                    autofocus />
            </div>

            {{-- color --}}
            <div class="mt-4">
                <x-input-label for="color" :value="__('Color')" />

                <select wire:model="color"
                    class="text-gray-900 h-[42px] shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 rounded-md w-full px-2"
                    id="colors">
                    <option disabled>Select a color</option>
                    @foreach ($colors as $color)
                        <option class="ml-4" value="{{ $color }}">{{ $color }}</option>
                    @endforeach
                </select>
            </div>

            {{-- categories' colors --}}
            {{-- for some reason, vite needs to "see" them before they are dinamically --}}
            {{-- compiled, otherwise the colors won't show --}}
            <div style="display: none;">
                <span class="bg-[blue]"></span>
                <span class="bg-[indigo]"></span>
                <span class="bg-[brown]"></span>
                <span class="bg-[black]"></span>
                <span class="bg-[yellow]"></span>
            </div>

            {{-- Categories list --}}
            <div class="my-4 space-y-1 border-gray-200 border-t-2 mt-6 pt-4">
                @forelse ($categories as $category)
                    <div class="border border-gray-400 flex items-center justify-between px-3 py-2 rounded text-sm hover:bg-gray-200"
                        wire:key="item-{{ $category->id }}">

                        <div class="flex items-center">
                            <span class="bg-[{{ $category->color }}] w-4 h-4 rounded-full mr-3"></span>

                            <button wire:click="selectCategoryToEdit({{ $category->id }})" type="button"
                                class="font-semibold hover:text-blue-500 text-gray-700">
                                {{ $category->name }}
                            </button>
                        </div>

                        <button wire:click="confirmDelete({{ $category->id }})" type="button">
                            <x-close-svg />
                        </button>
                    </div>

                @empty
                    <p>No categories yet...</p>
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

    {{-- delete category modal --}}
    <form wire:submit.prevent="deleteCategory">
        <x-modal wire:model.defer="showDeleteModal" delete="{{ true }}">

            <x-slot name="title">
                Are you sure?
            </x-slot>

            <x-slot name="message">
                <p>
                    Do you really wish to delete this category?
                </p>

                <p class="font-bold mt-1">
                    {{ $currentCategory->name }}
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

    {{-- update category modal --}}
    <form wire:submit.prevent="updateCategory">
        <x-modal wire:model.defer="showUpdateModal" delete="{{ false }}">

            <x-slot name="title">
                Update
            </x-slot>

            <x-slot name="message">

                {{-- category name --}}
                <div>
                    <x-input-label for="name" :value="__('Category')" />

                    <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" autofocus />
                </div>

                {{-- category name error --}}
                @error('name')
                    <span class="block mt-1 text-red-500 text-xs">{{ $message }}</span>
                @enderror

                {{-- color --}}
                <div class="mt-4">
                    <x-input-label for="color" :value="__('Color')" />

                    <select wire:model="color"
                        class="h-[42px] shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 rounded-md w-full px-2"
                        id="colors">
                        <option disabled>Select a color</option>
                        @foreach ($colors as $color)
                            <option class="ml-4" value="{{ $color }}">{{ $color }}</option>
                        @endforeach
                    </select>
                </div>

            </x-slot>

            <x-slot name="buttons">
                <x-primary-button wire:click="resetAttributes" type="button">
                    Cancel
                </x-primary-button>

                <x-primary-button class="bg-red-900 hover:bg-red-800 ml-2">
                    Update
                </x-primary-button>
            </x-slot>
        </x-modal>
    </form>

</div>
