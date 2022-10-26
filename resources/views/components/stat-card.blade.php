@props(['name', 'value'])

<div class="flex flex-col justify-between bg-white shadow-md rounded-lg px-4 py-6 overflow-auto">
    <h4 class="font-medium text-gray-600">{{ $name }}</h4>

    <div class="text-2xl sm:text-3xl font-bold mt-4">{{ $value }}</div>
</div>
