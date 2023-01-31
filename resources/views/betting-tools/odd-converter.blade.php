<x-app-layout>
    <x-slot:title>
        Odd Converter | Betsjournal
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-sm text-white leading-tight">
            {{ __('Odd Converter') }}
        </h2>
    </x-slot>

    <x-auth-card class="sm:max-w-lg">

        <p class="text-xl font-semibold">Odd Converter</p>

        <p class="font-medium text-sm text-gray-700">
            Convert odds type/format from either american to decimal or from decimal to american.
        </p>

        <!-- Odd Format -->
        <p class="mt-5 mb-1 font-medium text-sm">
            Odd Format:
        </p>

        <div class="flex items-center">

            <input class="mr-1" type="radio" name="to_odd_type" id="to_american" value="to_american">
            <x-input-label for="to_american" :value="__('To American')" />

            <input class="ml-4 mr-1" type="radio" name="to_odd_type" id="to_decimal" value="to_decimal">
            <x-input-label for="to_decimal" :value="__('To Decimal')" />

        </div>

        {{-- to oddtype error --}}
        <span class="block mt-1 text-red-500 text-xs" id="to_odd_type_error" style="display: none;"></span>

        <!-- odd -->
        <div class="mt-6">
            <x-input-label for="odd" :value="__('Odd')" />

            <x-text-input id="odd" class="block mt-1 w-full" type="number" step="0.001" name="odd" />
        </div>

        {{-- odd error --}}
        <span class="block mt-1 text-red-500 text-xs" id="odd_error" style="display: none"></span>

        <x-primary-button type="button" class="mt-4" id="btn">Convert</x-primary-button>

        <span class="bg-gray-700 block font-semibold text-sm mt-4 p-2 rounded-sm text-center text-gray-200"
            id="converted_odd" style="display: none"></span>

        <span class="bg-gray-700 block font-semibold text-sm mt-4 p-2 rounded-sm text-center text-gray-200"
            id="implied_probability" style="display: none"></span>

        {{-- betting tool info --}}
        <div class="flex items-center space-x-2 text-lg border-gray-200 border-t-2 mt-6 pt-4">
            <x-info-svg />

            <p>Tool Info</p>
        </div>

        <p class="mt-3 text-gray-700 text-sm">
            Being able to convert odds from decimal to american or vice-versa can be very helpful. The odds format you
            prefer is usually a personal preference and has a lot to do with where you come from and the format used in
            your location. Sometimes, you won't have the odds in your preferred format, so converting them can be
            helpful. Using our <span class="italic">Odd Converter</span> tool, you can easily convert from one format to
            another!
        </p>

        {{-- formulas --}}
        <p class="text-gray-700 text-sm mt-3">
            The following formulas can be used to convert between both formats:
        </p>

        {{-- american to decimal --}}
        <p class="text-gray-700 text-sm mt-5 font-semibold">
            American to decimal:
        </p>

        <p class="text-gray-700 text-sm mt-1">
            For negative odds:
        </p>

        <p class="bg-gray-700 py-4 rounded-sm text-center text-gray-200 text-sm mt-3">
            decimal &nbsp; = &nbsp; (100 / odd) &nbsp; + &nbsp; 1
        </p>

        <p class="text-gray-700 text-sm mt-3">
            For positive odds:
        </p>

        <p class="bg-gray-700 py-4 rounded-sm text-center text-gray-200 text-sm mt-3">
            decimal &nbsp; = &nbsp; (odd / 100) &nbsp; + &nbsp; 1
        </p>

        {{-- decimal to american --}}
        <p class="text-gray-700 text-sm mt-5 font-semibold">
            Decimal to american:
        </p>

        <p class="text-gray-700 text-sm mt-1">
            For odds of 2 or higher:
        </p>

        <p class="bg-gray-700 py-4 rounded-sm text-center text-gray-200 text-sm mt-3">
            american &nbsp; = &nbsp; (odd - 1) &nbsp; * &nbsp; 100
        </p>

        <p class="text-gray-700 text-sm mt-3">
            For odds smaller than 2:
        </p>

        <p class="bg-gray-700 py-4 rounded-sm text-center text-gray-200 text-sm mt-3">
            american &nbsp; = &nbsp; -100 &nbsp; / &nbsp; (odd - 1)
        </p>

    </x-auth-card>


    <script>
        // get DOM elements
        const button = document.getElementById('btn')
        const convertedOdd = document.getElementById('converted_odd')
        const impliedProbability = document.getElementById('implied_probability')
        const toOddTypeError = document.getElementById('to_odd_type_error')
        const oddError = document.getElementById('odd_error')

        button.addEventListener('click', () => {
            // get inputs
            const odd = document.getElementById('odd').value
            const toOddType = document.getElementsByName('to_odd_type')

            // reset error fields
            toOddTypeError.innerHTML = ''
            oddError.innerHTML = ''

            // reset and hide payout and profit fields
            convertedOdd.innerHTML = ''
            convertedOdd.style.display = "none"
            impliedProbability.innerHTML = ''
            impliedProbability.style.display = "none"

            // check if any input field is empty
            let emptyField = false

            if (!toOddType[0].checked && !toOddType[1].checked) {

                toOddTypeError.innerHTML = 'The to odd type field is required'
                toOddTypeError.style.display = "block"
                emptyField = true

            }

            if (!odd) {

                oddError.innerHTML = 'The odd field is required'
                oddError.style.display = "block"
                emptyField = true

            }

            if (!emptyField) {

                if (toOddType[0].checked) {

                    if (odd < 1) {
                        convertedOdd.innerHTML = 'N/A'
                        impliedProbability.innerHTML = 'N/A'
                    } else if (odd == 1) {
                        convertedOdd.innerHTML = 'N/A'
                        impliedProbability.innerHTML = '100%'
                    } else {
                        if (odd >= 2) {
                            convertedOdd.innerHTML = 'American: +' + decimalToAmerican(odd).toFixed(3)
                        } else {
                            convertedOdd.innerHTML = 'American: ' + decimalToAmerican(odd).toFixed(3)
                        }

                        impliedProbability.innerHTML = 'Implied Probability: ' + toImpliedProbability(odd).toFixed(
                                2) +
                            '%'
                    }

                } else if (toOddType[1].checked) {

                    const decimalOdd = americanToDecimal(odd).toFixed(3)
                    convertedOdd.innerHTML = 'Decimal: ' + decimalOdd
                    impliedProbability.innerHTML = 'Implied Probability: ' + toImpliedProbability(decimalOdd)
                        .toFixed(
                            2) + '%'

                }

                convertedOdd.style.display = "block"
                impliedProbability.style.display = "block"
            }
        })

        function americanToDecimal(odd) {
            if (odd > 0) {
                return (odd / 100) + 1
            } else {
                return (100 / Math.abs(odd)) + 1
            }
        }

        function decimalToAmerican(odd) {
            if (odd >= 2) {
                return (odd - 1) * 100
            } else {
                return -100 / (odd - 1)
            }
        }

        function toImpliedProbability(odd) {
            return (1 / odd) * 100
        }
    </script>
</x-app-layout>
