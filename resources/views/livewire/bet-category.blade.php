<div>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Bets / Categories / New') }}
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

                <select wire:model="color" class="shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 rounded-md w-full px-2" id="colors">
                    <option disabled>Select a color</option>
                    @foreach ($colors as $color)
                        <option class="ml-4" value="{{ $color }}">{{ $color }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Categories list --}}
            <div class="my-4 space-y-1 border-gray-200 border-t-2 mt-6 pt-4">
                @forelse ($categories as $category)
                    <div class="border border-gray-900 flex items-center justify-between px-3 py-2 rounded text-sm hover:bg-gray-200"
                        wire:key="item-{{ $category->id }}">

                        <div class="flex items-center">
                            <span class="bg-[{{ $category->color }}] w-4 h-4 rounded-full mr-3"></span>

                            <button wire:click="selectCategoryToEdit({{ $category->id }})" type="button"
                                class="font-semibold hover:text-blue-500">
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

            <div class="flex justify-end mt-4">
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

                    <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text"
                        autofocus />
                </div>

                {{-- color --}}
                <div class="mt-4">
                    <x-input-label for="color" :value="__('Color')" />

                    <select wire:model="color" class="block mt-1 w-full rounded" id="colors">
                        <option disabled>Select a color</option>
                        @foreach ($colors as $color)
                            <option class="ml-4" value="{{ $color }}">{{ $color }}</option>
                        @endforeach
                    </select>
                </div>

            </x-slot>

            <x-slot name="buttons">
                <x-primary-button 
                wire:click="resetAttributes" type="button">
                    Cancel
                </x-primary-button>

                <x-primary-button class="bg-red-900 hover:bg-red-800 ml-2">
                    Update
                </x-primary-button>
            </x-slot>
        </x-modal>
    </form>

</div>
