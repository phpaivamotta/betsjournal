@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1.5 border-b-4 border-indigo-400 text-sm font-medium leading-5 text-white focus:outline-none focus:border-gray-400 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1.5 border-b-4 border-transparent text-sm font-medium leading-5 text-white hover:text-gray-300 hover:border-gray-300 focus:outline-none focus:text-gray-400 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
