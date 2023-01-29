@if (session('subscription-added'))
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 5500)" 
        x-show="show"
        class="fixed bg-green-200 text-green-700 border border-solid border-green-700 py-2 px-4 rounded-md lg:rounded-xl ml-3 bottom-3 right-3 text-lg lg:text-sm">

        <div class="flex items-center space-x-2">
            <x-valuebets-icon class="w-6" color="green" />

            <p>
                {{ session('subscription-added') }}
            </p>
        </div>
        
    </div>
@endif
