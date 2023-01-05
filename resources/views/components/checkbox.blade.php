@props(['model', 'value'])

<div>
    <label for="{{ $value }}" class="inline-flex items-center">
        <input wire:model.defer="{{ $model }}" value="{{ $value }}" id="{{ $value }}" type="checkbox"
            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        <span class="ml-2 text-sm text-gray-600">{{ __($value) }}</span>
    </label>
</div>
