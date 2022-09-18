@props(['errors'])

@if ($errors->any())
    <div class="mb-4 p-2 border-[1.5px] border-red-600 rounded-md bg-red-200">
        <div class="font-medium text-red-600">
            {{ __('Whoops! Something went wrong.') }}
        </div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
