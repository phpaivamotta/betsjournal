@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block pl-3 pr-4 py-1 border-l-4 border-white text-xs text-white bg-blue-900 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'mt-1 block pl-3 pr-4 py-1 border-l-4 border-transparent text-xs text-white hover:border-white focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
