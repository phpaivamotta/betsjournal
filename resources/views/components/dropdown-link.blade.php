@props(['active'])

@php
    $classes = 'block px-4 py-2 text-sm leading-5 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out';

    if ($active) {
        $classes = $classes . ' bg-blue-900 text-white hover:text-black';
    } else {
        $classes = $classes . ' text-black';
    }
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>