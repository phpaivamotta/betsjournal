<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div {{ $attributes->merge(['class' => 'w-11/12 sm:w-full sm:max-w-md rounded-lg mt-6 px-6 py-4 bg-white shadow-md overflow-hidden']) }}>
        {{ $slot }}
    </div>
</div>