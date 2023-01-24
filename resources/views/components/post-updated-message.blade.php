<div x-data="{ show: false }"
    @profile-updated.window="show = true; setTimeout(() => show = false, 3500); message=$event.detail.message"
    x-show="show" class="mb-4 p-3 rounded text-center text-green-800 bg-green-300" style="display: none;">

    <p x-text="message"></p>

</div>
