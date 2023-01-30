@if (session('subscription-added'))
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 5500)" 
        x-show="show"
        class="fixed bg-green-200 text-green-700 border border-solid border-green-700 py-2 px-4 rounded-md lg:rounded-xl bottom-3 right-3 left-3 sm:left-auto text-lg lg:text-sm">

        <div class="flex items-center space-x-2">
            <x-valuebets-icon class="w-6" color="green" />

            <p class="font-semibold">
                {{ session('subscription-added') }}
            </p>
        </div>
        
    </div>
@endif
