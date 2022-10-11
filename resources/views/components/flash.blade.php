@if (session('success'))
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 3500)" 
        x-show="show"
        class="bg-gradient-to-r font-semibold from-emerald-600 py-2 rounded-md text-center text-white to-cyan-600 opacity-75">
        {{ session('success') }}
    </div>
@endif
