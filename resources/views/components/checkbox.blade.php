@props(['name'])

<div>
    <label for="{{ $name }}" class="inline-flex items-center">
        <input id="{{ $name }}" type="checkbox"
            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            name="{{ $name }}" value="{{ $name }}">
        <span class="ml-2 text-sm text-gray-600">{{ __($name) }}</span>
    </label>
</div>
