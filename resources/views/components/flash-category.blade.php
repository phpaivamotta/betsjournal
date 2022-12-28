@props(['status'])

<div 
    x-data="{ show: true }" 
    x-init="setTimeout(() => show = false, 3500)" 
    x-show="show"
    {{ $attributes->merge(['class' => 'mb-4 p-3 rounded text-center text-green-800']) }}
>

    {{ session($status) }}

</div>
