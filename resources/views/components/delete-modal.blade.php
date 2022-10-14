<div 
    x-data="{ show: @entangle($attributes->wire('model')) }" 
    x-show="show"
    @keydown.escape.window="show = false"
    style="display: none"
>

<div @click="show = false" class="fixed inset-0 bg-gray-900 opacity-70"></div>

    <div class="fixed bg-white shadow-md p-6 max-w-sm rounded-lg m-auto max-h-56 inset-0 overflow-auto" 
    x-show="show"
    x-transition
    >
        <div class="flex flex-col justify-between h-full">
            <header class="text-blue-900 text-xl font-bold">
                {{ $title }}
            </header>

            <main>
                {{ $message }}
            </main>

            <footer>
                {{ $buttons }}
            </footer>
        </div>
    </div>
</div>
