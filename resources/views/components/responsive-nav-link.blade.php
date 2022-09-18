@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block pl-3 pr-4 py-1 border-l-4 border-white text-xs text-white bg-blue-900 focus:outline-none focus:text-blue-900 focus:bg-white focus:border-indigo-200 transition duration-150 ease-in-out'
            : 'block pl-3 pr-4 py-1 border-l-4 border-transparent text-xs text-white focus:outline-none focus:text-blue-900 focus:bg-white focus:border-indigo-200 hover:border-white transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
